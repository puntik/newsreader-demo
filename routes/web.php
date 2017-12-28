<?php

Route::get('/', '\App\Http\Controllers\WelcomeController');

Route::get('/ca/{id}-{name}', '\App\Http\Controllers\HelloController')->name('category');

\Illuminate\Support\Facades\Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/search', 'SearchResultsController')->name('searchResult');

Route::get('/login/github', 'Auth\LoginController@redirectToProvider');
Route::get('/login/github/callback', 'Auth\LoginController@handleProviderCallback');
