<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use App\Userpackage;
use App\Curriculum;
use App\Subject;
use App\Lesson;
use App\LiveSession;
use App\Lessonpurchase;
use DateTime;
use DateInterval;

class HomeController extends Controller
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
    public function lost()
    {
        return view('lost_page');
    }
    public function index()
    {
        if(!Auth::user()->is_admin){
            abort(404); 
        }
        $recent_students = User::where('is_learner', true)
            ->orderBy('created_at', 'desc')->limit(10)->get()->toArray();
        return view('admin_home')->with([
            'students' => $recent_students
        ]);
    }

    public function teacher()
    {
        if(!Auth::user()->is_teacher){
            abort(404); 
        }
        $recent_lessons = Lesson::orderBy('created_at', 'desc')->where('teacher', Auth::user()->id)->limit(5)->get();
        return view('teacher_home')->with([
            'lessons' => $recent_lessons->toArray()
        ]);
    }
    public function corp_accounts()
    {
        $accounts = Lessonpurchase::where('buyer', Auth::user()->id)
            ->where('paid', true)->get()->toArray();
        if(!Auth::user()->is_cop){
            abort(404); 
        }
        return view('corp_accounts')->with([
            'accounts' => $accounts
        ]);
    }
    public function corporate()
    {
        if(!Auth::user()->is_cop){
            abort(404); 
        }
        if( !Session::has('order') || substr(session('order'), 0, 3) != 'CRP' ){
            Session::put('order', 'CRP-' . $this->createCode(6));
        }
        $lessons = [];
        $mylessons = Lessonpurchase::select('lesson')
            ->where('paid', true)
            ->where('buyer', Auth::user()->id)->get();
        if(!is_null($mylessons)){
            $l = $mylessons->toArray();
            $lessons = LiveSession::orderBy('created_at', 'desc')
                ->whereIn('id', $mylessons)->get();
        }
        return view('corp_home')->with([
            'mylessons' => $lessons,
            'lessons' => $this->live_lessons($mylessons)
        ]);
    }
    public function learner()
    {
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        if(!Auth::user()->is_paid){
            if( !Session::has('order') ){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $subscription_data = Userpackage::where('user', Auth::user()->id)
            ->where('package', Auth::user()->package)
            ->orderBy('id', 'desc')
            ->first();
        if(is_null($subscription_data)){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $subscription_data = $subscription_data->toArray();
        $subscription_date = explode('T', $subscription_data['created_at'])[0];
        $obj_date = new DateTime($subscription_date);
        $renew_date = $obj_date->format('Y-m-d');
        $subscription_data['start'] = $renew_date;
        $obj_date->add(new DateInterval('P'.$subscription_data['max_usage'].'M'));
        $expiry_date = $obj_date->format('Y-m-d');
        $u = Userpackage::find($subscription_data['id']);
        $u->expiry = $expiry_date;
        $u->save();
        $subscription_data['expiry'] = $expiry_date;
        $this->check_expiry();
        return view('learner_home')
            ->with([
                'subscription_data' => $subscription_data,
                'timetable' => $this->timetable()
            ]);
    }
    protected function timetable()
    {
        // date_default_timezone_set('Africa/Nairobi');
        $group = Auth::user()->group;
        $level = Auth::user()->level;
        $subjects = Subject::select('id')
            ->where('form_or_class', $level)
            ->where('is_active', true)
            ->where('is_what', $group)->get()->toArray();
        $topics = Curriculum::select('id')->whereIn('subject', $subjects)->get()->toArray();
        $lessons = Lesson::whereIn('topic', $topics)
            ->where('is_paid', true)
            ->where('is_active', true)
            ->where('type', 'LIVE')
            ->get()->toArray();

        $l = [];
        if(count($lessons)){
            foreach( $lessons as $_less):
                $i = [
                    'title' => $_less['sub_topic'],
                    'start' => date('Y-m-d H:i:s', strtotime($_less['zoom_time'])),
                    'className' => 'bg-info'
                ];
                array_push($l, $i);
            endforeach;
        }
        return $l;
    }
    protected function createCode($length = 20) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    protected function check_expiry()
    {
        $subscription_data = Userpackage::where('user', Auth::user()->id)
            ->where('package', Auth::user()->package)
            ->orderBy('id', 'desc')
            ->first();
        if(is_null($subscription_data)){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $end_date = date('Y-m-d', strtotime($subscription_data['expiry']));
        $today = date('Y-m-d');
        if($today > $end_date ){
            //set as expired
            $p = Userpackage::find($subscription_data['id']);
            $p->is_expired = true;
            $p->save();
            //remove user package
            $u = User::find(Auth::user()->id);
            $u->package = 0;
            $u->is_paid = false;
            $u->can_access_lesson = false;
            $u->save();
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
    }
    protected function live_lessons($mylessons)
    {
        return $lessons = LiveSession::orderBy('zoom_time', 'desc')
                ->whereNotIn('id', $mylessons)->get()->toArray();
    }
}
