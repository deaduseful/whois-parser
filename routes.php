<?php

Route::get('/', 'Controller@index')->name('index');
Route::post('/', 'Controller@store')->name('store');
