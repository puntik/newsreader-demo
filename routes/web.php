<?php

Route::get('/', '\App\Http\Controllers\WelcomeController');

Route::get('/hello/{id}', '\App\Http\Controllers\HelloController');

\Illuminate\Support\Facades\Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


