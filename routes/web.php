<?php

//simple router. for more complex app different types should be grouped and divided by type

Route::get('/', function () {
    return view('test-form');
});

Route::post('/api/entries', 'MainController@save');
