<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;
use Djordje\LaravelSentryBackend\Services\Notifiers\PasswordReset;
use Djordje\LaravelSentryBackend\Services\Validators\PasswordResetValidator;

class PasswordResetController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Notifiers\PasswordReset
	 */
	protected $reset;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Validators\PasswordResetValidator
	 */
	protected $validator;

	public function __construct(Users $users, PasswordReset $reset, PasswordResetValidator $validator)
	{
		$this->users = $users;
		$this->reset = $reset;
		$this->validator = $validator;
	}

	/**
	 * Show form for creating new password reset for user.
	 */
	public function create()
	{
		return View::make('laravel-sentry-backend::password-reset.create');
	}

	/**
	 * Store newly created password reset to storage and send email to user.
	 */
	public function store()
	{
		$user = $this->users->findByCredentials(array('email' => Input::get('email')));

		if ($user)
		{
			$this->reset->send($user);

			return Redirect::route('password-reset.create')->with('password_reset_sent', true);
		}

		return Redirect::route('password-reset.create')->withErrors(array('email' => $this->users->getError()));
	}

	/**
	 * Show form for creating new password to user with correct id and reset code.
	 *
	 * @param int $userId
	 * @param string $code
	 */
	public function edit($userId, $code)
	{
		$user = $this->users->findById($userId);

		if ($user && $user->checkResetPasswordCode($code))
		{
			return View::make('laravel-sentry-backend::password-reset.edit', compact('user', 'code'));
		}

		return Redirect::route('password-reset.create');
	}

	/**
	 * Updated user password in storage.
	 */
	public function update()
	{
		$id = Input::get('id');
		$code = Input::get('code');
		$user = $this->users->findById($id);

		if ($user && $code)
		{
			if ( ! $this->validator->validate(Input::all()))
			{
				return Redirect::route('password-reset.edit', array($id, $code))->withErrors($this->validator->getErrors());
			}

			if ($user->checkResetPasswordCode($code) && $user->attemptResetPassword($code, Input::get('password')))
			{
				return Redirect::route('session.create')->with('success_password_reset', true);
			}
		}

		return Redirect::route('password-reset.create');
	}

}