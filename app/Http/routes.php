<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Request;

Route::get('/', function () {
    $restaurants = \App\Restaurant::actual()->orderBy('name','ASC')->get();
    $cuisines = \App\Term::cuisines()->orderBy('title','ASC')->get();
    return view('index')->with(compact('restaurants', 'cuisines'));
});

Route::post('/', function () {
    $cuisine_filter = Request::input('cuisine-filter');
    $restaurants = \App\Restaurant::actual()->orderBy('name','ASC')->whereHas('terms', function ($query) use ($cuisine_filter) {
        $query->whereIN('id', $cuisine_filter);
    })->get();
    $cuisines = \App\Term::cuisines()->orderBy('title','ASC')->get();
    return view('index')->with(compact('restaurants', 'cuisines'));
});

Route::get('/api/restaurants', function() {

  $cuisine_filter = Request::input('cuisine_filter');
  $cuisine_filter = json_decode($cuisine_filter);
  $restaurants = \App\Restaurant::actual()->orderBy('name','ASC')->whereHas('terms', function ($query) use ($cuisine_filter) {
      $query->whereIN('id', $cuisine_filter);
  })->get();
  $cuisines = \App\Term::cuisines()->orderBy('title','ASC')->get();
  return view('partials.restaurant_group')->with(compact('restaurants', 'cuisines'));
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
