<?php namespace Djordje\LaravelSentryBackend\Services\Validators;

//use Illuminate\Support\Facades\Hash;

use Validator;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;

class PasswordUpdateValidator extends AbstractValidator {

	protected $rules = array(
		'old_password' => 'required|sentry_old_password',
		'password' => 'required|min:8|confirmed',
	);

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	public function __construct(Auth $auth)
	{
		$this->auth = $auth;
	}

	protected function createOldPasswordValidator()
	{
		$user = $this->auth->getUser();

		Validator::extend('sentry_old_password', function($attribute, $value, $parameters) use($user)
		{
			return $user->checkPassword($value);
		});
	}

	public function validate($data)
	{
		$this->createOldPasswordValidator();

		return parent::validate($data);
	}

}