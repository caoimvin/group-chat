<?php
use App\Events\MyWebsocketEvent;

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
    broadcast(new MyWebsocketEvent('my data'));
    return view('welcome');
});

Auth::routes();
Route::get('/chats', 'ChatsController@index');

Route::get('/messages', 'ChatsController@getMessages');
Route::post('/messages', 'ChatsController@saveMessage');

Route::get('/home', 'HomeController@index')->name('home');
