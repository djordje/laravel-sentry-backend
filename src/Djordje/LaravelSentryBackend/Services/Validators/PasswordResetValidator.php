<?php namespace Djordje\LaravelSentryBackend\Services\Validators;

class PasswordResetValidator extends AbstractValidator {

	protected $rules = array(
		'password' => 'required|min:8|confirmed'
	);

}