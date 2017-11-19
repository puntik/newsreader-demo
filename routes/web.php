<?php

Route::get('/', '\App\Http\Controllers\WelcomeController');

Route::get('/ca/{id}-{name}', '\App\Http\Controllers\HelloController')->name('category');

\Illuminate\Support\Facades\Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


