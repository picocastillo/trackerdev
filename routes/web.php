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
Route::get('/methodology', function () {
    return view('welcome');
});
Route::get('/projects', function () {
    return view('welcome');
});
Route::get('/contact', function () {
    return view('welcome');
});
Route::post('/contact-form', 'PublicController@contactForm');
Auth::routes(['register' => false, 'reset' => false]);


Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'LanguageController@switchLang']);

Route::get('/home', 'DashboardController@index')->name('home');

Route::group(['middleware' => ['auth','role:senior']], function() {
    
    /////////////////////////////
    //////////////Projects
    /////////////////////////////
    
    Route::get('/project/create', 'ProjectController@create');
    Route::get('/project/{id}/edit', 'ProjectController@edit');
    Route::put('/project/{id}/edit', 'ProjectController@update');
    Route::get('/project', 'ProjectController@index');
    Route::post('/project/create', 'ProjectController@store');
    Route::get('/project/{id}', 'ProjectController@show');


    /////////////////////////////
    //////////////Iterations
    /////////////////////////////
/* 
    Route::get('/iteration/{project_id}/edit', 'IterationController@edit');
    Route::get('/iteration/{project_id}', 'IterationController@create');
    Route::post('/iteration', 'IterationController@store');
    Route::post('/iteration/update/', 'IterationController@update');
 */

    /////////////////////////////
    //////////////Reports
    /////////////////////////////


    Route::post('/reports', 'ReportController@create');
    Route::post('/reports/new', 'ManagerController@createReport');
    Route::post('/reports/details', 'ManagerController@detailReport');
    Route::post('/reports/store', 'ReportController@store');

    

    /////////////////////////////
    //////////////Tasks
    /////////////////////////////

    Route::get('/task/create', 'TaskController@create');
    Route::get('/task/delete/{id}', 'TaskController@delete');
    Route::post('/task/create', 'TaskController@store');
    Route::get('/task/{task_id}/edit', 'TaskController@edit');
    Route::get('/task/{task_id}/create-a-child', 'TaskController@createAChild');
    Route::post('/task/update', 'TaskController@update');
    Route::post('/tasks/add-time', 'TaskController@addTime');
    Route::post('/tasks/add-watcher', 'TaskController@addWatcher');

    
});

    


Route::group(['middleware' => ['auth','permissions']], function() {
    
    
    /////////////////////////////
    //////////////Tasks
    /////////////////////////////
    Route::get('/tasks/{id}', 'TaskController@show');
    Route::post('/tasks/add-item', 'TaskController@addItem');
    Route::get('/new-task', 'TaskController@createTask');
    Route::post('/new-task', 'TaskController@storeTask');
    Route::get('/new-task/{task_id}', 'TaskController@editTask');
    Route::post('/new-task/update', 'TaskController@update');
    Route::post('/tasks/add-effort', 'TaskController@storeEffort');
    Route::post('/tasks/complete-item', 'TaskController@completeItem');
    Route::post('/tasks/add-message', 'TaskController@addMessage');
    Route::post('/tasks/assign-to', 'TaskController@assignTo');
    Route::post('/tasks/attach-file', 'TaskController@attachFile');
    Route::post('/tasks/change-to-testing', 'TaskController@changeToTesting');
    Route::post('/tasks/change-to-feedback', 'TaskController@changeToFeedback');
    Route::post('/tasks/change-to-finished', 'TaskController@changeToFinished');
    Route::post('/tasks/charge-effort', 'TaskController@chargeEffort');

});

Route::group(['middleware' => ['auth']], function() {
    
    Route::get('/checking-testing', function(){
        return view('checking');
    });
    Route::get('/wiki', function(){
        return view('wiki');
    });

    /////////////////////////////
    //////////////Reports
    /////////////////////////////

    Route::get('/reports', 'ReportController@index');
    Route::get('/reports/{id}', 'ReportController@show');

});











// Route::get('/profile', 'DashboardController@showProfile');
// Route::get('/deposits', 'DashboardController@showDeposits');





//pages



