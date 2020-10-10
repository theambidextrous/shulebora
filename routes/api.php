<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/api/v1', function (Request $request) {
    return response(['status' => -211, 'message' => 'point of no return']);
});
Route::fallback(function () {
    return response(['status'=>-211, 'message' => 'oops! Congrats! you\'ve reached point of no return']);
});
/** callback */
// Route::prefix('/input-streams')->group(['as' => 'api.'], function() {
//     Route::any('/pay/c2b', 'CallbackController@c2b')->name('callback');
//     Route::any('/pay/express', 'CallbackController@express')->name('express');
//     Route::any('/pay/status/{order}', 'CallbackController@status_check')->name('status_check');
// });
/** 
 * this is users group of endpoints
 */
Route::prefix('/users')->group( function() {
    /** test sms */
    Route::post('/test/sms/{phone}/msg/{msg}', 'api\LoginController@sendSMS');
    /** request code */
    Route::post('/request/code/{phone}', 'api\LoginController@requestcode');
    /** validate code */
    Route::post('/validate/phone/{phone}/code/{code}', 'api\LoginController@validatecode');
    /** change password */
    Route::post('/change/password', 'api\LoginController@pwdreset');
    
    /** all users login */
    Route::post('/login', 'api\LoginController@login');
    Route::post('/new/student', 'api\LoginController@new_student');
    Route::post('/new/corporate', 'api\LoginController@new_corporate');
    /** register student */
    Route::post('/students/register', 'api\UserController@new_student');
    /** register corporate */
    Route::post('/corporates/register', 'api\UserController@new_corporate');
    /** =======AUTHENTICATED ROUTES======================================= */
    Route::middleware('auth:api')->group( function(){
        /** is logged in  */
        Route::post('/loginstatus', 'api\LoginController@is_active');
        /** userinfo */
        Route::post('/data', 'api\LoginController@userdata');
    });
}); 
/** 
 * this is topics group of endpoints
 */

Route::prefix('/topics')->group( function() {
    /** forum pic stream  */
    Route::any('/doc/forum/stream/{file}', 'api\TopicController@forum_stream');
    /** file stream free */
    Route::any('/doc/free/stream/{file}', 'api\TopicController@free_stream');
    /** free lessons */
    Route::any('/free/lessons', 'api\TopicController@freeLessons');
    /** =======AUTHENTICATED ROUTES======================================= */
    Route::middleware('auth:api')->group( function(){
        /** subjects */
        Route::post('/user/subjects', 'api\TopicController@user_subject');
        /** subject topics */
        Route::post('/subject/topics/{subject}', 'api\TopicController@subject_topics');
        /** get by subject*/
        Route::post('/bysubject/{subject}', 'api\TopicController@bysubject');
        /** topic lessons */
        Route::post('/topic/lessons/{topic}', 'api\TopicController@topic_lessons');
        /** assignments */
        Route::post('/user/assignments', 'api\TopicController@assign');
        /** papers */
        Route::post('/user/papers', 'api\TopicController@paper');
        /** payments */
        Route::post('/payments', 'api\TopicController@payments');
        /** file stream */
        Route::any('/doc/stream/{file}', 'api\TopicController@stream');
        /** download */
        Route::any('/doc/download/{file}', 'api\TopicController@stream_download');
        /** packages */
        Route::post('/packages', 'api\TopicController@packages');
        /** place student order */
        Route::post('/student/order', 'api\TopicController@student_order');
         /** place corpo order */
         Route::post('/corporate/order', 'api\TopicController@corp_order');
        /** request mpesa expres */
        Route::post('/mpesa/mpesa', 'api\TopicController@mpesa');
        /** live lessons --- all */
        Route::post('/live/lessons', 'api\TopicController@livelessons');
        /** live lessons --- paid */
        Route::post('/live/lessons/paid', 'api\TopicController@livelessons_paid');
        /** trnxs */
        Route::post('/corp/transactions', 'api\TopicController@payments');
        /** forums */
        Route::post('/ask/question', 'api\TopicController@ask');
        Route::post('/my/asked/questions', 'api\TopicController@my_questions');
    });
    /** request mpesa order status */
    Route::post('/mpesa/status/{order}', 'api\TopicController@mstatus');
});
