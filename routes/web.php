<?php

Route::get('/', '\App\Http\Controllers\WelcomeController');

Route::get('/ca/{id}-{name}', '\App\Http\Controllers\HelloController')->name('category');

Route::get('/search', 'SearchResultsController')->name('searchResult');

Route::get('/login/github', 'Auth\LoginController@redirectToProvider');
Route::get('/login/github/callback', 'Auth\LoginController@handleProviderCallback');

// Authentication Routes...
// Auth::routes();
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
