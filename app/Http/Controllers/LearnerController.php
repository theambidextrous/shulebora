<?php

namespace App\Http\Controllers;
use App\User;
use App\Userpackage;
use App\Curriculum;
use App\Subject;
use App\Lesson;
use App\Assignment;
use App\Paper;
use App\Order;
use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LearnerController extends Controller
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
    public function topic_lesson($topic)
    {
        $this->check_expiry(Auth::user()->id);
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        $newUser = User::find(Auth::user()->id);
        if(!$newUser->is_paid){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $lessons = Lesson::where('topic', $topic)
            ->where('is_paid', true)
            ->where('is_active', true)
            ->get()->toArray();
        
        $topic = Curriculum::find($topic)->toArray();
        return view('learner_lessons')->with([
            'lessons' => $lessons,
            'topic' => $topic
        ]);
    }
    public function assigns()
    {
        $this->check_expiry(Auth::user()->id);
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        $newUser = User::find(Auth::user()->id);
        if(!$newUser->is_paid){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $group = Auth::user()->group;
        $level = Auth::user()->level;
        $subjects = Subject::select('id')
            ->where('form_or_class', $level)
            ->where('is_active', true)
            ->where('is_what', $group)->get()->toArray();
        $assignments = Assignment::whereIn('subject', $subjects)
            ->where('is_active', true)
            ->get()->toArray();
        return view('learner_assignments')
            ->with([
                'assignments' => $assignments
            ]);
    }
    public function papers()
    {
        $this->check_expiry(Auth::user()->id);
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        $newUser = User::find(Auth::user()->id);
        if(!$newUser->is_paid){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $group = Auth::user()->group;
        $level = Auth::user()->level;
        $subjects = Subject::select('id')
            ->where('form_or_class', $level)
            ->where('is_active', true)
            ->where('is_what', $group)->get()->toArray();
        $papers = Paper::whereIn('subject', $subjects)
            ->where('is_active', true)
            ->get()->toArray();
        return view('learner_papers')
            ->with([
                'papers' => $papers
            ]);
    }
    public function accounts()
    {
        $this->check_expiry(Auth::user()->id);
        if(!Auth::user()->is_learner){
            abort(404); 
        }
        $newUser = User::find(Auth::user()->id);
        if(!$newUser->is_paid){
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        $accounts = Order::where('buyer', Auth::user()->id)
            ->where('paid', true)
            ->get()->toArray();
        return view('learner_accounts')
            ->with([
                'accounts' => $accounts
            ]);
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
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy'));
            // return true;
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
            if(!Session::has('order')){
                Session::put('order', $this->createCode(6));
            }
            return redirect(route('buy')); 
        }
        return true;
    }
    // protected function check_expiry()
    // {
    //     $subscription_data = Userpackage::where('user', Auth::user()->id)
    //         ->where('package', Auth::user()->package)
    //         ->orderBy('id', 'desc')
    //         ->first();
    //     if(is_null($subscription_data)){
    //         if(!Session::has('order')){
    //             Session::put('order', $this->createCode(6));
    //         }
    //         return redirect(route('buy')); 
    //     }
    //     if(!is_null($subscription_data['expiry'])){
    //         $end_date = date('Y-m-d', strtotime($subscription_data['expiry']));
    //         $today = date('Y-m-d');
    //         if($today > $end_date ){
    //             //set as expired
    //             $p = Userpackage::find($subscription_data['id']);
    //             $p->is_expired = true;
    //             $p->save();
    //             //remove user package
    //             $u = User::find(Auth::user()->id);
    //             $u->package = 0;
    //             $u->is_paid = false;
    //             $u->can_access_lesson = false;
    //             $u->save();
    //             if(!Session::has('order')){
    //                 Session::put('order', $this->createCode(6));
    //             }
    //             return redirect(route('buy')); 
    //         }
    //     }else{

    //     }
    // }
}
