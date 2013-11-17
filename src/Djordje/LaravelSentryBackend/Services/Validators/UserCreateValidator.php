<?php namespace Djordje\LaravelSentryBackend\Services\Validators;

class UserCreateValidator extends AbstractValidator {

	protected $rules = array(
		'email' => 'required|email|unique:users',
		'password' => 'required|min:8|confirmed',
	);

}