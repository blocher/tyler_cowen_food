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
//use Request;

Route::get('/', function () {
    $cuisines = \App\Term::cuisines()->orderBy('title','ASC')->lists('title','id');
    return view('index')->with(compact('cuisines'));
});

Route::get('/api/restaurants', function() {

  $restaurants = \App\Restaurant::actual();

  $cuisine_filter = Request::input('cuisine_filter');
  $cuisine_filter = json_decode($cuisine_filter);
  if (is_array($cuisine_filter) && count($cuisine_filter)>0) {
    $restaurants = $restaurants->whereHas('terms', function ($query) use ($cuisine_filter) {
      $query->whereIN('id', $cuisine_filter);
    });
  }

  $lat = Request::input('lat');
  $lng = Request::input('lng');
  $sort = Request::input('sort');

  if (!empty($lat) && !empty($lng)) {
    $restaurants = $restaurants
    ->nearby($lat, $lng);
  } else if ($sort=='date') {
    $restaurants = $restaurants
      ->orderBy('added_on','DESC');
  } else {
    $restaurants = $restaurants
      ->orderBy('name','ASC');
  }

  $restaurants = $restaurants->get();

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
