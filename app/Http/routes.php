<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
	// return view('welcome');
    return view('user_crud');
});

Route::get('/users', function (){
	
	$user = new StdClass();
	$user->id = 1;
	$user->firstname = 'Joao';
	$user->lastname = 'Batista';
	$user->age =33;

	$user2 = new StdClass();
	$user2->id = 2;
	$user2->firstname = 'Jorge';
	$user2->lastname = 'Resende';
	$user2->age =30;
	
	$content = array($user, $user2);
	return json_encode($content);
});

Route::post('/users', function (){
	return 1;
});

Route::put('/users/{id}', function (){
	return 1;
});

Route::delete('/users/{id}', function (){
	return 1;
});

Route::get('/users/{id}', function (){
	
	$user = new StdClass();
	$user->id = 1;
	$user->firstname = 'Joao';
	$user->lastname = 'batista';
	$user->age =33;

	$content = $user;
	return json_encode($content);
});