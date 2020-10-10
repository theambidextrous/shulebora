<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'WelcomeController@index')->name('welcome');
Route::get('/invalid/action', 'HomeController@lost')->name('lost');
/**guest student */
Route::get('/new/student', 'GuestController@showRegistrationForm')->name('student_form');
Route::post('/new/student', 'GuestController@register')->name('student_create');
/**guest corporate */
Route::get('/new/corporate', 'CorpController@showRegistrationForm')->name('corporate_form');
Route::post('/new/corporate', 'CorpController@register')->name('corporate_create');
/** payment
 * learners
 */
Route::get('/shule/bora/learner/buy', 'BuyerController@showPackagesForm')->name('buy');
Route::post('/shule/bora/learner/buy', 'BuyerController@order')->name('order');
Route::post('/shule/bora/learner/pay', 'BuyerController@pay')->name('pay');
Route::get('/shule/bora/learner/paymentform', 'BuyerController@showPaymentForm')->name('payform');
/** payment
 * coprorates
 */
Route::post('/shule/bora/corp/buy', 'BuyerController@corp_order')->name('corp_order');
Route::post('/shule/bora/corp/pay', 'BuyerController@corp_pay')->name('corp_pay');
Route::get('/shule/bora/corp/paymentform', 'BuyerController@corp_showPaymentForm')->name('corp_payform');

/** callback */
Route::any('/input-streams/pay/c2b', 'CallbackController@c2b')->name('callback');
Route::any('/input-streams/pay/express', 'CallbackController@express')->name('express');
Route::any('/input-streams/pay/status/{order}', 'CallbackController@status_check')->name('status_check');
/** freebies */
Route::get('/free-lessons/ebooks', 'GuestController@free_files')->name('free_files');
Route::get('/free-lessons/videos', 'GuestController@free_videos')->name('free_videos');

Auth::routes();

Route::get('/shule/bora', 'HomeController@index')->name('school');
Route::get('/shule/bora/teacher', 'HomeController@teacher')->name('teacher');
Route::get('/shule/bora/learner', 'HomeController@learner')->name('learner');
Route::get('/shule/bora/corporate', 'HomeController@corporate')->name('corporate');
/** content stream unsecured*/
Route::get('/shule/bora/stream/{file}', 'StreamController@stream')->name('finder');
/** profile */
Route::get('/shule/bora/profile', 'ProfileController@index')->name('profile');
Route::post('/shule/bora/profile', 'ProfileController@update')->name('profile_update');
/** admin routes */
Route::group(['prefix' => 'shule/bora/admin', 'as' => 'admin.'], function(){
    /** students */
    Route::get('students', 'StudentController@index')->name('students');
    Route::get('students/{id}', 'StudentController@show')->name('student');
    Route::post('students', 'StudentController@create')->name('add_student');
    Route::post('students/{id}', 'StudentController@update')->name('update_student');
    Route::get('students/find/high', 'StudentController@high')->name('high_student');
    Route::get('students/find/prim', 'StudentController@prim')->name('prim_student');
    Route::post('students/access/{id}', 'StudentController@update_access')->name('update_student_access');
    /**forms */
    Route::get('forms', 'FormController@index')->name('forms');
    Route::get('forms/{id}', 'FormController@show')->name('form');
    Route::post('forms', 'FormController@create')->name('add_form');
    Route::post('forms/{id}', 'FormController@update')->name('update_form');
    /** classes */
    Route::get('classes', 'GclassController@index')->name('classes');
    Route::get('classes/{id}', 'GclassController@show')->name('class');
    Route::post('classes', 'GclassController@create')->name('add_class');
    Route::post('classes/{id}', 'GclassController@update')->name('update_class');
    /** groups */
    Route::get('groups', 'GroupController@index')->name('groups');
    Route::get('groups/{id}', 'GroupController@show')->name('group');
    Route::post('groups', 'GroupController@create')->name('add_group');
    Route::post('groups/{id}', 'GroupController@update')->name('update_group');
    /** subjects */
    Route::get('subjects', 'SubjectController@index')->name('subjects');
    Route::get('subjects/{id}', 'SubjectController@show')->name('subject');
    Route::post('subjects', 'SubjectController@create')->name('add_subject');
    Route::get('subjects/find/high', 'SubjectController@high')->name('high_subject');
    Route::get('subjects/find/prim', 'SubjectController@prim')->name('prim_subject');
    Route::post('subjects/{id}', 'SubjectController@update')->name('update_subject');
    Route::post('subjects/topics/{id}', 'SubjectController@update_topics')->name('update_subject_topics');
    Route::post('subjects/topic/drop/{id}', 'SubjectController@drop_topic')->name('drop_topic');
    /** teachers */
    Route::get('teachers', 'TeacherController@index')->name('teachers');
    Route::get('teachers/{id}', 'TeacherController@show')->name('teacher');
    Route::post('teachers', 'TeacherController@create')->name('add_teacher');
    Route::post('teachers/{id}', 'TeacherController@update')->name('update_teacher');
    Route::post('teachers/access/{id}', 'TeacherController@update_access')->name('update_teacher_access');
    Route::post('teachers/subs/{id}', 'TeacherController@update_subs')->name('update_teacher_subs');
    Route::post('teachers/t/{id}/sub/{sub}', 'TeacherController@drop_sub')->name('drop_sub');
    /** timetables */
    Route::get('timetables', 'TimetableController@index')->name('timetables');
    Route::get('timetables/{id}', 'TimetableController@show')->name('timetable');
    Route::post('timetables', 'TimetableController@create')->name('add_timetable');
    Route::post('timetables/{id}', 'TimetableController@update')->name('update_timetable');
    /** lessons */
    Route::get('lessons', 'LessonController@index')->name('lessons');
    Route::get('lessons/{id}', 'LessonController@show')->name('lesson');
    Route::post('lessons', 'LessonController@create')->name('add_lesson');
    Route::post('lessons/{id}', 'LessonController@update')->name('update_lesson');
    /** live sessions */
    Route::get('csessions', 'CsessionController@index')->name('csessions');
    Route::get('csessions/{id}', 'CsessionController@show')->name('csession');
    Route::post('csessions', 'CsessionController@create')->name('add_csession');
    Route::post('csessions/{id}', 'CsessionController@update')->name('update_csession');
    /** content stream secured*/
    Route::get('stream/{file}', 'LessonController@stream')->name('securefinder');
    
    /** assignments */
    Route::get('assignments', 'AssignmentController@index')->name('assignments');
    Route::get('assignments/{id}', 'AssignmentController@show')->name('assignment');
    Route::post('assignments', 'AssignmentController@create')->name('add_assignment');
    Route::post('assignments/{id}', 'AssignmentController@update')->name('update_assignment');
    /** papers */
    Route::get('papers', 'PaperController@index')->name('papers');
    Route::get('papers/{id}', 'PaperController@show')->name('paper');
    Route::post('papers', 'PaperController@create')->name('add_paper');
    Route::post('papers/{id}', 'PaperController@update')->name('update_paper');
    /** packages */
    Route::get('packages', 'PackageController@index')->name('packages');
    Route::get('packages/{id}', 'PackageController@show')->name('package');
    Route::post('packages', 'PackageController@create')->name('add_package');
    Route::post('packages/{id}', 'PackageController@update')->name('update_package');
    /** payments */
    Route::get('payments', 'PaymentController@index')->name('payments');
    Route::get('payments/failed', 'PaymentController@failed')->name('failed_payments');

    Route::post('payments', 'PaymentController@create')->name('add_payment');
    Route::post('payments/{id}', 'PaymentController@update')->name('update_payment');
});

/** teacher routes */
Route::group(['prefix' => 'shule/bora/teacher', 'as' => 'teacher.'], function(){
    /** lessons */
    Route::get('lessons', 'LessonController@teacher_index')->name('lessons');
    Route::get('lessons/{id}', 'LessonController@teacher_show')->name('lesson');
    Route::post('lessons', 'LessonController@teacher_create')->name('add_lesson');
    Route::post('lessons/{id}', 'LessonController@teacher_update')->name('update_lesson');
    /** forums */
    Route::get('forums/{subject}', 'SubjectController@teacher_forum')->name('forums');
    Route::post('forums/answer', 'SubjectController@teacher_forum_answer')->name('fanswer');
    /** subjects */
    Route::get('subjects', 'SubjectController@teacher_index')->name('subjects');
    /** papers */
    Route::get('papers', 'PaperController@teacher_index')->name('papers');
    Route::get('papers/{id}', 'PaperController@teacher_show')->name('paper');
    Route::post('papers', 'PaperController@teacher_create')->name('add_paper');
    Route::post('papers/{id}', 'PaperController@teacher_update')->name('update_paper');
    /** assignments */
    Route::get('assignments', 'AssignmentController@teacher_index')->name('assignments');
    Route::get('assignments/{id}', 'AssignmentController@teacher_show')->name('assignment');
    Route::post('assignments', 'AssignmentController@teacher_create')->name('add_assignment');
    Route::post('assignments/{id}', 'AssignmentController@teacher_update')->name('update_assignment');
});
/** learner routes */
Route::group(['prefix' => 'shule/bora/learner', 'as' => 'learner.'], function(){
    Route::get('lessons/topic/{topic}', 'LearnerController@topic_lesson')->name('topic_lesson');
    Route::get('assignments', 'LearnerController@assigns')->name('assigns');
    Route::get('papers', 'LearnerController@papers')->name('papers');
    Route::get('accounts', 'LearnerController@accounts')->name('accounts');
});
/** corporate routes */
Route::group(['prefix' => 'shule/bora/corporate', 'as' => 'corporate.'], function(){
    Route::get('accounts', 'HomeController@corp_accounts')->name('accounts');
});
