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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/shule/bora', 'HomeController@index')->name('school');
/** admin routes */
Route::group(['prefix' => 'shule/bora/admin', 'as' => 'admin.'], function(){
    /** students */
    Route::get('students', 'StudentController@index')->name('students');
    Route::get('students/{id}', 'StudentController@show')->name('student');
    Route::post('students', 'StudentController@create')->name('add_student');
    Route::post('students/{id}', 'StudentController@update')->name('update_student');
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
    Route::post('subjects/{id}', 'SubjectController@update')->name('update_subject');

    /** teachers */
    Route::get('teachers', 'TeacherController@index')->name('teachers');
    Route::get('teachers/{id}', 'TeacherController@show')->name('teacher');
    Route::post('teachers', 'TeacherController@create')->name('add_teacher');
    Route::post('teachers/{id}', 'TeacherController@update')->name('update_teacher');
    Route::post('teachers/access/{id}', 'TeacherController@update_access')->name('update_teacher_access');
});
