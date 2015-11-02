<?php

Route::group(['prefix' => 'auth', 'namespace' => 'Modules\User\Http\Controllers'], function()
{
	Route::get('/login', ['as' => 'auth.loginGet', 'uses' => 'Auth\AuthController@index']);
    Route::post('/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@postLogin']);
    Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@getLogout']);

    // Registration routes...
    Route::get('/register', ['as' => 'auth.get.register', 'uses' => 'Auth\AuthController@getRegister']);
    Route::post('/register', ['as' => 'auth.post.register', 'uses' => 'Auth\AuthController@postRegister']);

    Route::post('/user/change-password', ['as' => 'user.change-password', 'uses' => 'Admin\UserController@changePassword']);

    Route::controller('password', 'Auth\PasswordController',[
        'getEmail' => 'auth.reset.password.getEmail',
        'postEmail' => 'auth.reset.password.postEmail',
        'getReset' => 'auth.reset.password.getReset',
        'postReset' => 'auth.reset.password.postReset',
    ]);
});

Route::group(['prefix' => 'admin', 'namespace' => 'Modules\User\Http\Controllers\Admin'], function(){

    Route::resource('user', 'UserController');
    Route::resource('permission', 'PermissionController');
    Route::resource('role', 'RoleController');

});

Route::get('/learning/user/public/{id}', [
        'as' => 'learning.user.profile.public',
        'uses' => 'Modules\User\Http\Controllers\Learning\UserController@getPublicProfile']
);

Route::group(['prefix' => 'learning', 'namespace' => 'Modules\User\Http\Controllers\Learning', 'middleware' => 'auth'], function(){

    Route::controller('user', 'UserController',[
        'getProfile' => 'learning.user.profile',
    ]);

});

