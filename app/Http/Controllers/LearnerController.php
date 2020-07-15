<?php

namespace App\Http\Controllers;
use App\Lesson;
use App\Curriculum;
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
        if(!Auth::user()->is_learner){
            abort(404); 
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
        return view('learner_assignments');
    }
    public function papers()
    {
        return view('learner_papers');
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
}
