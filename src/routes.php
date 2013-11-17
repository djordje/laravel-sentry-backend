<?php

/*
|--------------------------------------------------------------------------
| Session routes
|--------------------------------------------------------------------------
|
*/
Route::get('/login', array(
	'as' => 'session.create',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\SessionController@create'
));
Route::post('/login', array(
	'as' => 'session.store',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\SessionController@store'
));
Route::delete('/logout', array(
	'as' => 'session.destroy',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\SessionController@destroy'
));

/*
|--------------------------------------------------------------------------
| Profile routes
|--------------------------------------------------------------------------
|
*/
Route::resource(
	'profile', 'Djordje\LaravelSentryBackend\Controllers\ProfileController',
	array('except' => array('show', 'destroy'))
);
Route::get('profile/{id}/activate/{code}', array(
	'as' => 'profile.activate',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\ProfileController@activate'
));

/*
|--------------------------------------------------------------------------
| Profile password routes
|--------------------------------------------------------------------------
|
*/
Route::resource(
	'profile.password', 'Djordje\LaravelSentryBackend\Controllers\ProfilePasswordController',
	array('only' => array('create', 'store'))
);

/*
|--------------------------------------------------------------------------
| Password reset routes
|--------------------------------------------------------------------------
|
*/
Route::get('password-reset', array(
	'as' => 'password-reset.create',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\PasswordResetController@create'
));
Route::post('password-reset', array(
	'as' => 'password-reset.store',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\PasswordResetController@store'
));
Route::get('password-reset/{id}/{code}', array(
	'as' => 'password-reset.edit',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\PasswordResetController@edit'
));
Route::put('password-reset', array(
	'as' => 'password-reset.update',
	'uses' => 'Djordje\LaravelSentryBackend\Controllers\PasswordResetController@update'
));

/*
|--------------------------------------------------------------------------
| Protect user management
|--------------------------------------------------------------------------
| This section require superuser permission!
|
*/
Route::group(array('before' => 'AllowForGroup:Admin'), function()
{
	/*
	|--------------------------------------------------------------------------
	| Users routes
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('users', 'Djordje\LaravelSentryBackend\Controllers\UsersController');
	Route::patch('users/{id}/activate', array(
		'as' => 'users.activate',
		'uses' => 'Djordje\LaravelSentryBackend\Controllers\UsersController@activate'
	));

	/*
	|--------------------------------------------------------------------------
	| Groups routes
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource('groups', 'Djordje\LaravelSentryBackend\Controllers\GroupsController');

	/*
	|--------------------------------------------------------------------------
	| User groups routes
	|--------------------------------------------------------------------------
	|
	*/
	Route::resource(
		'users.groups',
		'Djordje\LaravelSentryBackend\Controllers\UserGroupsController',
		array('except' => array('show', 'edit', 'update'))
	);

});