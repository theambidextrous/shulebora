<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\User;
use App\Lesson;
use App\Curriculum;
use App\Subject;
use App\Tsubject;
use Storage;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class LessonController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        return view('admin_lessons')->with([
            'lessons' => $this->lesson_list(),
            'topics' => $this->topic_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_index()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        return view('teacher_lessons')->with([
            'lessons' => $this->teacher_lesson_list(),
            'topics' => $this->teacher_topic_list(),
        ]);
    }
    public function show($id)
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $lesson = Lesson::find($id)->toArray();
        return view('admin_lessons_update')->with([
            'lesson' => $lesson,
            'lessons' => $this->lesson_list(),
            'topics' => $this->topic_list(),
            'teachers' => $this->teacher_list()
        ]);
    }
    public function teacher_show($id)
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        $lesson = Lesson::where('id', $id)
            ->where('created_by', Auth::user()->id)->first()->toArray();
        return view('teacher_lessons_update')->with([
            'lesson' => $lesson,
            'lessons' => $this->teacher_lesson_list(),
            'topics' => $this->teacher_topic_list(),
        ]);
    }
    public function create(Request $request)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'topic' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'type' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'sub_topic' => 'string|required',
                'introduction' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_link'))
            {
                return view('admin_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom link',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_time'))
            {
                return view('admin_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom time',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( !$request->hasfile('file_content') && 
                !$request->hasfile('video_content') && 
                !$request->hasfile('audio_content') &&
                $request->get('type') != 'LIVE')
            {
                return view('admin_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'You must upload either video, audio or file',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }else{
                $input['file_content'] = 'n/a';
                $input['video_content'] = 'n/a';
                $input['audio_content'] = 'n/a';
            }
            /** zoom  */
            if( $request->get('type') != 'LIVE'){
                $input['zoom_link'] = 'n/a';
                $input['zoom_time'] = 'n/a';
                $input['zoom_help_note'] = 'n/a';
            }
            if($request->hasfile('file_content')){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }else{
                $input['file_content'] = 'n/a';
            }
            if($request->hasfile('video_content')){
                $video_content = $request->file('video_content');
                $video_content_name = $file_uuid . $video_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $video_content, $video_content_name);
                $input['video_content'] = $video_content_name;
            }else{
                $input['video_content'] = 'n/a';
            }
            if($request->hasfile('audio_content')){
                $audio_content = $request->file('audio_content');
                $audio_content_name = $file_uuid . $audio_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $audio_content, $audio_content_name);
                $input['audio_content'] = $audio_content_name;
            }else{
                $input['audio_content'] = 'n/a';
            }
            $input['created_by'] = Auth::user()->id;
            $lesson = Lesson::create($input);
            if($lesson){
                if( $request->get('type') == 'LIVE' ){
                    /** notify */
                    $notify_topic = $input['topic'];
                    $topicObject = Curriculum::find($notify_topic);
                    $subjectObject = Subject::find($topicObject->subject);
                    $recipientsObject = User::select('phone')->where('is_learner', true)
                        ->where('group', $subjectObject->is_what)
                        ->where('level', $subjectObject->form_or_class)
                        ->get();
                    if(!is_null($recipientsObject))
                    {
                        $phones_to_receive_sms = $recipientsObject->toArray();
                        $_d = date('M jS, Y', strtotime($input['zoom_time']));
                        $_t = date('h:i a', strtotime($input['zoom_time']));
                        $date_and_time = $_d . ' @ ' . $_t;
                        $_all_count = count($phones_to_receive_sms);
                        $counter_id = 1;
                        $message = 'New LIVE Class - ' . $date_and_time . '. Subject: ' . ucwords(strtolower($subjectObject->name)) .PHP_EOL. '. Topic: '.ucwords(strtolower($topicObject->topic)).')';
                        $smslist = [];
                        foreach ($phones_to_receive_sms as $key => $phone) {
                            array_push($smslist, [
                                "apikey" => Config::get('app.app_sms_api_key'),
                                "partnerID" => Config::get('app.app_sms_partner_id'),
                                "pass_type" => "plain",
                                "clientsmsid" => $counter_id.time(),
                                "message" => $message,
                                "shortcode" => Config::get('app.app_sms_shortcode'),
                                "mobile" => substr($this->format_tel($phone['phone']), 1, 12)
                            ]);
                            if( $counter_id%20 == 0 || $counter_id >= $_all_count)
                            {
                                $_body_data = ['count' => count($smslist), 'smslist' => $smslist];
                                $this->sendSmsBulk($_body_data);
                                $smslist = [];
                            }
                            $counter_id ++;
                        }
                    }
                }
                return view('admin_lessons')->with([
                    'flag' => 1,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'lesson created successfully',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return view('admin_lessons')->with([
                'flag' => 2,
                'lessons' => $this->lesson_list(),
                'msg' => 'lesson creation failed',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return view('admin_lessons')->with([
                'flag' => 3,
                'lessons' => $this->lesson_list(),
                'msg' => 'Databse error. Most likely entry already exists',//$ex->getMessage(),
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_create(Request $request)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'topic' => 'string|required|not_in:nn',
                'type' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'sub_topic' => 'string|required',
                'introduction' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_link'))
            {
                return view('teacher_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom link',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_time'))
            {
                return view('teacher_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom time',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( !$request->hasfile('file_content') && 
                !$request->hasfile('video_content') && 
                !$request->hasfile('audio_content') &&
                $request->get('type') != 'LIVE')
            {
                return view('teacher_lessons')->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'You must upload either video, audio or file',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }else{
                $input['file_content'] = 'n/a';
                $input['video_content'] = 'n/a';
                $input['audio_content'] = 'n/a';
            }
            /** zoom  */
            if( $request->get('type') != 'LIVE'){
                $input['zoom_link'] = 'n/a';
                $input['zoom_time'] = 'n/a';
                $input['zoom_help_note'] = 'n/a';
            }
            if($request->hasfile('file_content')){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }else{
                $input['file_content'] = 'n/a';
            }
            if($request->hasfile('video_content')){
                $video_content = $request->file('video_content');
                $video_content_name = $file_uuid . $video_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $video_content, $video_content_name);
                $input['video_content'] = $video_content_name;
            }else{
                $input['video_content'] = 'n/a';
            }
            if($request->hasfile('audio_content')){
                $audio_content = $request->file('audio_content');
                $audio_content_name = $file_uuid . $audio_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $audio_content, $audio_content_name);
                $input['audio_content'] = $audio_content_name;
            }else{
                $input['audio_content'] = 'n/a';
            }
            $input['created_by'] = $input['teacher'] = Auth::user()->id;
            $lesson = Lesson::create($input);
            if($lesson){
                if( $request->get('type') == 'LIVE' ){
                    /** notify */
                    $notify_topic = $input['topic'];
                    $topicObject = Curriculum::find($notify_topic);
                    $subjectObject = Subject::find($topicObject->subject);
                    $recipientsObject = User::select('phone')->where('is_learner', true)
                        ->where('group', $subjectObject->is_what)
                        ->where('level', $subjectObject->form_or_class)
                        ->get();
                    if(!is_null($recipientsObject))
                    {
                        $phones_to_receive_sms = $recipientsObject->toArray();
                        $_d = date('M jS, Y', strtotime($input['zoom_time']));
                        $_t = date('h:i a', strtotime($input['zoom_time']));
                        $date_and_time = $_d . ' @ ' . $_t;
                        $_all_count = count($phones_to_receive_sms);
                        $counter_id = 1;
                        $message = 'New LIVE Class - ' . $date_and_time . '. Subject: ' . ucwords(strtolower($subjectObject->name)) .PHP_EOL. '. Topic: '.ucwords(strtolower($topicObject->topic)).')';
                        $smslist = [];
                        foreach ($phones_to_receive_sms as $key => $phone) {
                            array_push($smslist, [
                                "apikey" => Config::get('app.app_sms_api_key'),
                                "partnerID" => Config::get('app.app_sms_partner_id'),
                                "pass_type" => "plain",
                                "clientsmsid" => $counter_id.time(),
                                "message" => $message,
                                "shortcode" => Config::get('app.app_sms_shortcode'),
                                "mobile" => substr($this->format_tel($phone['phone']), 1, 12)
                            ]);
                            if( $counter_id%20 == 0 || $counter_id >= $_all_count)
                            {
                                $_body_data = ['count' => count($smslist), 'smslist' => $smslist];
                                $this->sendSmsBulk($_body_data);
                                $smslist = [];
                            }
                            $counter_id ++;
                        }
                    }
                }
                return view('teacher_lessons')->with([
                    'flag' => 1,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'lesson created successfully',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return view('teacher_lessons')->with([
                'flag' => 2,
                'lessons' => $this->lesson_list(),
                'msg' => 'lesson creation failed',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);

        } catch(\Illuminate\Database\QueryException $ex){ 
            return view('teacher_lessons')->with([
                'flag' => 3,
                'lessons' => $this->lesson_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'topic' => 'string|required|not_in:nn',
                'teacher' => 'string|required|not_in:nn',
                'type' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'sub_topic' => 'string|required',
                'introduction' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_link'))
            {
                return back()->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom link',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_time'))
            {
                return back()->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom time',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( !$request->hasfile('file_content') && 
                !$request->hasfile('video_content') && 
                !$request->hasfile('audio_content') &&
                $request->get('type') != 'LIVE')
            {
                return back()->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'You must upload either video, audio or file',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }else{
                $input['file_content'] = 'n/a';
                $input['video_content'] = 'n/a';
                $input['audio_content'] = 'n/a';
            }
            /** zoom  */
            if( $request->get('type') != 'LIVE'){
                $input['zoom_link'] = 'n/a';
                $input['zoom_time'] = 'n/a';
                $input['zoom_help_note'] = 'n/a';
            }
            if($request->hasfile('file_content')){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }else{
                $input['file_content'] = 'n/a';
            }
            if($request->hasfile('video_content')){
                $video_content = $request->file('video_content');
                $video_content_name = $file_uuid . $video_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $video_content, $video_content_name);
                $input['video_content'] = $video_content_name;
            }else{
                $input['video_content'] = 'n/a';
            }
            if($request->hasfile('audio_content')){
                $audio_content = $request->file('audio_content');
                $audio_content_name = $file_uuid . $audio_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $audio_content, $audio_content_name);
                $input['audio_content'] = $audio_content_name;
            }else{
                $input['audio_content'] = 'n/a';
            }
            $input['created_by'] = Auth::user()->id;
            $lesson = Lesson::find($id)->update($input);
            if($lesson){
                return back()->with([
                    'flag' => 1,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'lesson updated successfully',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'lessons' => $this->lesson_list(),
                'msg' => 'lesson update failed',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'lessons' => $this->lesson_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function teacher_update(Request $request, $id)
    {
        $file_uuid = (string) Str::uuid();
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        try { 
            Validator::make($request->all(),[
                'topic' => 'string|required|not_in:nn',
                'type' => 'string|required|not_in:nn',
                'category' => 'string|required|not_in:nn',
                'sub_topic' => 'string|required',
                'introduction' => 'string|required'
            ])->validate();
            $input = $request->all();
            $input['is_paid'] = false;
            $input['is_active'] = true;
            if($input['category'] == 'PAID'){
                $input['is_paid'] = true;
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_link'))
            {
                return back()->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom link',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            if( $request->get('type') == 'LIVE' && 
                !$request->get('zoom_time'))
            {
                return back()->with([
                    'flag' => 3,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'Live lessons must have zoom time',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            /** zoom  */
            if( $request->get('type') != 'LIVE'){
                $input['zoom_link'] = 'n/a';
                $input['zoom_time'] = 'n/a';
                $input['zoom_help_note'] = 'n/a';
            }
            if($request->hasfile('file_content')){
                $file_content = $request->file('file_content');
                $file_content_name = $file_uuid . $file_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $file_content, $file_content_name);
                $input['file_content'] = $file_content_name;
            }else{
                $input['file_content'] = 'n/a';
            }
            if($request->hasfile('video_content')){
                $video_content = $request->file('video_content');
                $video_content_name = $file_uuid . $video_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $video_content, $video_content_name);
                $input['video_content'] = $video_content_name;
            }else{
                $input['video_content'] = 'n/a';
            }
            if($request->hasfile('audio_content')){
                $audio_content = $request->file('audio_content');
                $audio_content_name = $file_uuid . $audio_content->getClientOriginalName();
                Storage::disk('local')
                    ->putFileAs('cls/trt/content', $audio_content, $audio_content_name);
                $input['audio_content'] = $audio_content_name;
            }else{
                $input['audio_content'] = 'n/a';
            }
            $input['created_by'] = $input['teacher'] = Auth::user()->id;
            $lesson = Lesson::find($id)->update($input);
            if($lesson){
                return back()->with([
                    'flag' => 1,
                    'lessons' => $this->lesson_list(),
                    'msg' => 'lesson updated successfully',
                    'topics' => $this->topic_list(),
                    'teachers' => $this->teacher_list()
                ]);
            }
            return back()->with([
                'flag' => 2,
                'lessons' => $this->lesson_list(),
                'msg' => 'lesson update failed',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);

          } catch(\Illuminate\Database\QueryException $ex){ 
            return back()->with([
                'flag' => 3,
                'lessons' => $this->lesson_list(),
                'msg' => 'Databse error. Most likely entry already exists',
                'topics' => $this->topic_list(),
                'teachers' => $this->teacher_list()
            ]);
          }
    }
    public function stream($f_str)
    {
        $filename = ('app/cls/trt/content/'.$f_str);
        return response()->download(storage_path($filename), null, [], null);
    }
    protected function lesson_list()
    {
        return Lesson::where('is_active', true)->orderBy('created_at', 'DESC')->get()->toArray();
    }
    protected function teacher_lesson_list()
    {
        return Lesson::where('created_by', Auth::user()->id)->where('is_active', true)->get()->toArray();
    }
    protected function teacher_topic_list()
    {
        $assigned_sub = Tsubject::select('subject')
            ->where('teacher', Auth::user()->id)->get()->toArray();
        return Curriculum::where('is_active', true)->whereIn('subject', $assigned_sub)->get()->toArray();
    }
    protected function topic_list()
    {
        return Curriculum::where('is_active', true)->orderBy('subject', 'desc')->get()->toArray();
    }
    protected function teacher_list()
    {
        return User::where('is_teacher', true)->get()->toArray();
    }
    protected function class_list()
    {
        return Gclass::where('is_active', true)->get()->toArray();
    }
    protected function sendSmsBulk($body){
        try{
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, Config::get('app.app_sms_api_url_bulk'));
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json')); 
            $data_string = json_encode($body);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
            $curl_response = curl_exec($curl);
            return true;
        }catch(Exception $e ){
            return false;
        }
    }
    protected function format_tel($tel){
        // print_r($tel);
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
}

