<?php

Route::get('/', '\App\Http\Controllers\WelcomeController');
Route::get('/hello/{id}', '\App\Http\Controllers\HelloController');
