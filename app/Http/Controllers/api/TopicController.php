<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Subject;
use App\Lesson;
use App\Curriculum;
use App\Assignment;
use App\Lessonpurchase;
use App\Order;
use App\Package;
use App\Userpackage;
use App\Download;
use App\LiveSession;
use App\Paper;
use App\Forum;
use Storage;
use Illuminate\Support\Str;
use Config;
/** mpesa */
use App\Http\Controllers\MpesaExpressController;
use App\Http\Controllers\MpesaController;
/** mail */
use Illuminate\Support\Facades\Mail;

class TopicController extends Controller
{
    public function subject_topics($sbj){
        $this->check_expiry(Auth::user()->id);
        $user = User::find(Auth::user()->id);
        $obj = Subject::find($sbj);
        $subject = 'None';
        if(!is_null($obj)){
            $subject = $obj->name;
        }
        if(!$user->is_paid){
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'subject' => $subject,
                    'data' => []
                ]
            ], 200);
        }
        $topics = Curriculum::where('subject', $sbj)->where('is_active', true)->orderBy('number', 'asc')->get();
        if(is_null($topics))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'subject' => $subject,
                    'data' => []
                ]
            ], 200);
        }
        $topics = $topics->toArray();
        $tpc = [];
        foreach ($topics as $value) {
            $value['pdf'] = Lesson::where('topic', $value['id'])->where('file_content', '!=', 'n/a')->where('is_paid', true)->count();
            $value['video'] = Lesson::where('topic', $value['id'])->where('video_content', '!=', 'n/a')->where('is_paid', true)->count();
            array_push($tpc, $value);
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'subject' => $subject,
                'data' => $tpc
            ]
        ], 200);
    }
    public function freeLessons()
    {
        
        $lessons = Lesson::where('is_active', true)
            ->where('video_content', '!=', 'n/a')
            ->where('type', 'RECORDED')
            ->where('is_paid', false)->get();
        if(is_null($lessons))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'data' => []
                ]
            ], 200);
        }
        $lessons = $lessons->toArray();
        $ll = [];
        foreach( $lessons as $les ){
            $topic_meta = Curriculum::find($les['topic']);
            $subject_meta = Subject::find($topic_meta['subject']);
            $les['subject'] = $subject_meta['name'];
            $les['topic'] = $topic_meta['topic'];
            array_push($ll, $les);
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $ll
            ]
        ], 200);
    }
    public function topic_lessons(Request $request, $topic)
    {
        $topic_title = 'None';
        $topic_obj = Curriculum::find($topic);
        if(!is_null($topic_obj)){
            $topic_title = $topic_obj->topic;
        }
        $this->check_expiry(Auth::user()->id);
        $user = User::find(Auth::user()->id);
        if(!$user->is_paid){
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'topic' => $topic_title,
                    'data' => []
                ]
            ], 200);
        }
        $lessons = Lesson::where('topic', $topic)->where('is_active', true)
        ->where('is_paid', true)->orderBy('type', 'ASC')->get();

        if(is_null($lessons))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'topic' => $topic_title,
                    'data' => []
                ]
            ], 200);
        }
        $lessons = $lessons->toArray();
        $ll = [];
        $today_ = date('Y-m-d');
        foreach( $lessons as $les ){
            $lday = date( 'Y-m-d', strtotime($les['zoom_time']) );
            if($les['type'] == 'LIVE' && $lday < $today_ ){}
            else {
                $les['token'] = $request->bearerToken();
                array_push($ll, $les);
            }
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'topic' => $topic_title,
                'data' => $ll
            ]
        ], 200);
    }
    public function assign()
    {
        $this->check_expiry(Auth::user()->id);
        $user = User::find(Auth::user()->id);
        // $user = Auth::user();
        if(!$user->is_paid){
            return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => []
            ]
        ], 200);
        }
        $sub_id = Subject::select(['id'])->where('form_or_class', $user->level)
            ->where('is_what', $user->group)
            ->get()->toArray();
        $assignments = Assignment::whereIn('subject', $sub_id)
            ->where('is_paid', true)
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
        $a = [];
        foreach ( $assignments as $ass )
        {
            $ass['subject'] = Subject::find($ass['subject'])->name;
            array_push($a, $ass);
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $a
            ]
        ], 200);
    }
    public function paper()
    {
        $this->check_expiry(Auth::user()->id);
        $user = User::find(Auth::user()->id);
        // $user = Auth::user();
        if(!$user->is_paid){
            return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => []
            ]
        ], 200);
        }
        $sub_id = Subject::select(['id'])->where('form_or_class', $user->level)
            ->where('is_what', $user->group)
            ->get()->toArray();
        $papers = Paper::whereIn('subject', $sub_id)->orderBy('subject', 'desc')
            ->where('is_paid', true)
            ->get()->toArray();
        $a = [];
        foreach ( $papers as $ass )
        {
            $ass['subject'] = Subject::find($ass['subject'])->name;
            array_push($a, $ass);
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $a
            ]
        ], 200);

    }
    public function payments()
    {
        $p = Order::where('buyer', Auth::user()->id)
            ->where('paid', true)
            ->limit(100)
            ->get();
        if(Auth::user()->is_cop){
            $p = Lessonpurchase::where('buyer', Auth::user()->id)
                ->where('paid', true)
                ->limit(100)
                ->get();
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $p
            ]
        ], 200);
    }
    public function forum_stream($fileid)
    {
        $filename = ('app/cls/trt/content/' . $fileid);
        if( is_file(storage_path($filename)) )
        {
            return response()->download(storage_path($filename), null, [], null);
        }
        else
        {
            return response(['status' => -211,'message' => 'file not found',], 404);
        }
    }
    public function free_stream($fileid)
    {
        $isFree = Lesson::where('video_content', $fileid)->where('is_paid', false)->count();
        if( $isFree )
        {
            $filename = ('app/cls/trt/content/' . $fileid);
            if( is_file(storage_path($filename)) )
            {
                return response()->download(storage_path($filename), null, [], null);
            }
            else
            {
                return response(['status' => -211,'message' => 'file not found',], 404);
            }
        }else{
            return response(['status' => -211,'message' => 'file not found',], 404);
        }
    }
    public function stream($fileid)
    {
        $filename = ('app/cls/trt/content/'.$fileid);
        if( is_file(storage_path($filename)) )
        {
            return response()->download(storage_path($filename), null, [], null);
        }
        else {
            return response(['status' => -211,'message' => 'file not found',], 404);
        }
    }
    public function stream_download($fileid)
    {
        if( $this->can_download(Auth::user()->id) )
        {
            $filename = ('app/cls/trt/content/'.$fileid);
            if( is_file(storage_path($filename)) )
            {
                return response()->download(storage_path($filename), null, [], null);
            }
            else {
                return response(['status' => -211,'message' => 'file not found',], 404);
            }
        }else{
            return response(['status' => -211,'message' => 'max downloads for the day reached',], 504);
        }
    }
    public function packages()
    {
        $p = Package::where('is_active', true)->get();
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $p
            ]
        ], 200);
    }
    public function user_subject(){
        $this->check_expiry(Auth::user()->id);
        $user = User::find(Auth::user()->id);
        // $user = Auth::user();
        $p = Subject::where('is_active', true)
            ->where('form_or_class', $user->level)
            ->where('is_what', $user->group)->get();
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $p
            ]
        ], 200);
    }
    public function student_order(Request $request)
    {
        if(!$request->user()->is_learner){
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }
        try { 
            Validator::make($request->all(),[
                'mpackage' => 'required',
                'orderid' => 'string|required'
            ])->validate();
            $input = $request->all();
            $packdata = Package::find($input['mpackage']);
            $input['package'] = $input['mpackage'];
            $input['cost'] = floor($packdata->price);
            $input['buyer'] = $request->user()->id;
            $order = Order::where('orderid', $input['orderid'])->where('paid', false)->first();
            if($order){
                $order->cost = $input['cost'];
                $order->package = $input['package'];
                $order->save();
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $order
                    ]
                ], 200);
            }
            Order::create($input);
            $neworder = Order::where('orderid', $input['orderid'])->first();
            if($neworder){
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $neworder
                    ]
                ], 200);
            }
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }catch(\Illuminate\Database\QueryException $ex){ 
            return response([
                'status' => -211,
                'message' => 'failed request' . $ex->getMessage(),
            ], 401); 
        }
    }
    public function corp_order(Request $request )
    {
        if(!$request->user()->is_cop){
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }
        try { 
            Validator::make($request->all(),[
                'lesson' => 'required',
                'orderid' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['cost'] = floor(LiveSession::find($input['lesson'])->price);
            $input['buyer'] = $request->user()->id;
            $order = Lessonpurchase::where('orderid', $input['orderid'])->where('paid', false)->first();
            if($order){
                $order->cost = $input['cost'];
                $order->lesson = $input['lesson'];
                $order->save();
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $order
                    ]
                ], 200);
            }
            Lessonpurchase::create($input);
            $neworder = Lessonpurchase::where('orderid', $input['orderid'])->first();
            if($neworder){
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $order
                    ]
                ], 200);
            }
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401);
        }catch(\Illuminate\Database\QueryException $ex){ 
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401);
        }
    }
    /*** forums ---- ask questions */
    public function ask(Request $request)
    {
        Validator::make($request->all(),[
            'subject' => 'integer|required',
            'topic' => 'integer|required',
            'question' => 'string|required',
        ])->validate();
        $input = $request->all();
        //
        if($request->hasfile('q_image')){
            $file_content = $request->file('q_image');
            $file_content_name = (string) Str::uuid() . $file_content->getClientOriginalName();
            Storage::disk('local')
                ->putFileAs('cls/trt/content', $file_content, $file_content_name);
            $input['q_image'] = $file_content_name;
        }
        //
        $input['asked_by'] = Auth::user()->id;
        if( Forum::create($input))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => $this->forums($request->get('subject'))
            ], 200);
        }
        return response([
            'status' => -211,
            'message' => 'failed request',
        ], 403);
    }
    protected function forums($sbj)
    {
        $data = [];
        // $questions = Forum::where('asked_by', Auth::user()->id)
        //     ->where('subject', $sbj)->get();
        $questions = Forum::where('subject', $sbj)
            ->orderBy('created_at', 'desc')
            ->get();
        if( !is_null($questions) )
        {
            $questions = $questions->toArray();
            foreach ($questions as $value) {
                $value['subject'] = Subject::find($value['subject'])->name;
                $value['topic'] = Curriculum::find($value['topic'])->topic;
                if( Auth::user()->id != $value['asked_by'] ){
                    $value['asked_by'] = "By " . User::find($value['asked_by'])->name;
                }else{
                    $value['asked_by'] = "By me";
                }
                array_push($data, $value);
            }
        }
        $payload = [
            'topics' => Curriculum::where('subject', $sbj)->get(), 
            'subject' =>  $sbj.'~'.Subject::find($sbj)->name,
            'data' => $data
        ];
        return $payload;
    }
    public function my_questions(Request $request)
    {
        Validator::make($request->all(),[
            'subject' => 'integer|required',
        ])->validate();
        $data = [];
        // $questions = Forum::where('asked_by', Auth::user()->id)
        //     ->where('subject', $request->get('subject'))->get();
        $questions = Forum::where('subject', $request->get('subject'))
            ->orderBy('created_at', 'desc')
            ->get();
        if( !is_null($questions) )
        {
            $questions = $questions->toArray();
            foreach ($questions as $value) {
                $value['token'] = $request->bearerToken();
                $value['subject'] = Subject::find($value['subject'])->name;
                $value['topic'] = Curriculum::find($value['topic'])->topic;
                if( Auth::user()->id != $value['asked_by'] ){
                    $value['asked_by'] = "By " . User::find($value['asked_by'])->name;
                }else{
                    $value['asked_by'] = "By me";
                }
                array_push($data, $value);
            }
        }
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'topics' => Curriculum::where('subject', $request->get('subject'))->get(), 
                'subject' =>  $request->get('subject').'~'.Subject::find($request->get('subject'))->name,
                'data' => $data
            ]
        ], 200);
    }
    public function livelessons()
    {
        $today = date('Y-m-d');
        $whereNotIn = Lessonpurchase::select(['lesson'])
            ->where('buyer', Auth::user()->id)
            ->where('paid', 1)
            ->get()
            ->toArray();
        $lessons = LiveSession::whereNotIn('id', $whereNotIn)
            ->where('zoom_time', '>=', $today)->get();

        if(is_null($lessons))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'data' => []
                ]
            ], 200);
        }
        $lessons = $lessons->toArray();
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $lessons
            ]
        ], 200);
    }
    public function livelessons_paid()
    {
        $today = date('Y-m-d');
        $whereIn = Lessonpurchase::select(['lesson'])
            ->where('buyer', Auth::user()->id)
            ->where('paid', 1)
            ->get()
            ->toArray();
        $lessons = LiveSession::whereIn('id', $whereIn)
            ->where('zoom_time', '>=', $today)->get();

        if(is_null($lessons))
        {
            return response([
                'status' => 0,
                'message' => 'success request',
                'payload' => [
                    'data' => []
                ]
            ], 200);
        }
        $lessons = $lessons->toArray();
        return response([
            'status' => 0,
            'message' => 'success request',
            'payload' => [
                'data' => $lessons
            ]
        ], 200);
    }
    public function mpesa(Request $request)
    {
        try { 
            Validator::make($request->all(),[
                // 'phone' => 'string|required',
                'orderid' => 'string|required'
            ])->validate();
            $the_order = Order::where('orderid', $request->get('orderid'))->first();
            if(!$request->user()->is_learner){
                $the_order = Lessonpurchase::where('orderid', $request->get('orderid'))->first();
            }
            if(is_null($the_order)){
                return response([
                    'status' => -211,
                    'message' => 'failed request',
                ], 401); 
            }
            $the_order = $the_order->toArray();
            $input = $request->all();
            $input['phone'] = Auth::user()->phone;
            $phone_n = $this->format_tel($input['phone']);
            $phone_n = substr($phone_n, 1, 12);
            $input['phone'] = $phone_n;
            $input['cost'] = floor($the_order['cost']);
            $mpesa_instructions = $this->mpesa_process(Config::get('app.app_mpesa_paybill'), $input['orderid'], $input['cost']);
            // $input['cost'] = 10;
            $express = new MpesaExpressController(
                Config::get('app.app_mpesa_c_key'),
                Config::get('app.app_mpesa_c_secret'),
                Config::get('app.app_mpesa_paybill'),
                Config::get('app.app_mpesa_passkey'),
                [Config::get('app.app_mpesa_env')],
                Config::get('app.app_mpesa_trans_type'),
                $input['cost'],
                $input['phone'],
                route('express'),
                $input['orderid'],
                'Shulebora',
                'no remarks'
            );
            // $c2b = new MpesaController(
            //     Config::get('app.app_mpesa_c2b_c_key'),
            //     Config::get('app.app_mpesa_c2b_c_secret'),
            //     Config::get('app.app_mpesa_c2b_paybill'),
            //     Config::get('app.app_mpesa_c2b_phone'),
            //     $input['cost'],
            //     $input['orderid'],
            //     [Config::get('app.app_mpesa_env'), route('callback'), route('callback')]
            // );
            $a = $express->TriggerStkPush();//CreateToken();
            if(json_decode($a)->ResponseCode == '0'){
                $o = Order::where('orderid', $input['orderid'])->first();
                if(!$request->user()->is_learner){
                    $o = Lessonpurchase::where('orderid', $input['orderid'])->first();
                }
                $o->payref = json_decode($a)->CheckoutRequestID;
                $o->save();
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'data' => $the_order,
                        'mpesa' => [
                            'a' => "Dear customer, A payment notification has been sent to your phone. Enter PIN to complete order.",
                            'phone' => Auth::user()->phone,
                            // 'instructions' => $mpesa_instructions
                        ]
                    ]
                ], 200);
            }else{
                return response([
                    'status' => -211,
                    'message' => 'Mpesa request failed. Try again',
                ], 401);
            }
        }catch(\Illuminate\Database\QueryException $ex){ 
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }catch(Exception $ex){ 
            return response([
                'status' => -211,
                'message' => 'failed request',
            ], 401); 
        }
    }

    public function mstatus($order)
    {
        $ord = Order::where('orderid', strtoupper($order))->first();
        if( substr($order, 0, 3) == 'CRP' ) // corporate
        {
            $ord = Lessonpurchase::where('orderid', strtoupper($order))->first();
        }
        if(!is_null($ord)){
            if( $ord->paid )
            {
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'order' => $ord,
                        'status' => 0
                    ]
                ], 200);
            }
            else
            {
                return response([
                    'status' => 0,
                    'message' => 'success request',
                    'payload' => [
                        'order' => $ord,
                        'status' => 1
                    ]
                ], 200);
            }
        }else{
            return response([
                'status' => 0,
                'message' => 'no order pending for this customer',
                'payload' => [
                    'status' => 2
                ]
            ], 200); 
        }
        
    }
    protected function check_expiry($user)
    {
        $subscription_data = Userpackage::where('user', $user)
            ->where('package', Auth::user()->package)
            ->orderBy('id', 'desc')
            ->first();
        if(is_null($subscription_data)){
            $u = User::find($user);
            $u->is_paid = false;
            $u->can_access_lesson = false;
            $u->package = 0;
            $u->save();
            return true;
        }
        $end_date = date('Y-m-d', strtotime($subscription_data['expiry']));
        $today = date('Y-m-d');
        if($today > $end_date ){
            //set as expired
            $p = Userpackage::find($subscription_data['id']);
            $p->is_expired = true;
            $p->save();
            $u = User::find($user);
            $u->package = 0;
            $u->is_paid = false;
            $u->can_access_lesson = false;
            $u->save();
            return true;
        }
        return true;
    }
    protected function can_download($user) /** user max allowed downloads per day is 6 */
    {
        $date = date('Y-m-d');
        //
        $has_downloaded = Download::where('user', $user)->where('date', $date)->count();
        if( $has_downloaded > 6 ){
            return false;
        }
        $input['user'] = $user;
        $input['date'] = $date;
        Download::create($input);
        return true;
    }
    protected function format_tel($tel){
        $phone =  '';
        $tel = str_replace(' ', '', $tel);
        if( substr( $tel, 0, 2 ) === "07" && strlen($tel) == 10 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 2 ) === "01" && strlen($tel) == 10 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 4 ) === "2547" && strlen($tel) == 12 ){
            return $phone = '+'.$tel;
        }
        elseif( substr( $tel, 0, 4 ) === "2541" && strlen($tel) == 12 ){
            return $phone = '+'.$tel;
        }
        elseif( substr( $tel, 0, 5 ) === "25407" && strlen($tel) == 13 ){
            $phone = strstr($tel, '0');
            return	$phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 5 ) === "25401" && strlen($tel) == 13 ){
            $phone = strstr($tel, '0');
            return	$phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 6 ) === "+25407" && strlen($tel) == 14 ){
            $phone = strstr($tel, '0');
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 6 ) === "+25401" && strlen($tel) == 14 ){
            $phone = strstr($tel, '0');
            return $phone = '+254'.(int)$phone;
        }
        elseif( substr( $tel, 0, 1 ) === "7" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 1 ) === "1" && strlen($tel) == 9 ){
            return $phone = '+254'.(int)$tel;
        }
        elseif( substr( $tel, 0, 5 ) === "+2547" && strlen($tel) == 13 ){
            return $phone = $tel;
        }
        elseif( substr( $tel, 0, 5 ) === "+2541" && strlen($tel) == 13 ){
            return $phone = $tel;
        }else{
            throw new Exception('Invalid phone number. try format 07XXX or 01XXX');
        }
    }
    protected function mpesa_process($paybill, $invoice, $amt)
    {
        return $paybill . '~' . $invoice . '~' . $amt;
    }
}
