<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;
use Djordje\LaravelSentryBackend\Services\Validators\PasswordUpdateValidator;

class ProfilePasswordController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Validators\PasswordUpdateValidator
	 */
	protected $validator;

	public function __construct(Users $users, Auth $auth, PasswordUpdateValidator $validator)
	{
		$this->users = $users;
		$this->auth = $auth;
		$this->validator = $validator;
	}

	/**
	 * Display form for changing password.
	 *
	 * @param int $userId
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function create($userId)
	{
		$user = $this->auth->getUser();

		if ($user && ($userId == $user->id))
		{
			return View::make('laravel-sentry-backend::profilepassword.create', compact('user'));
		}

		return Redirect::route('profile.index');
	}

	/**
	 * Store new password.
	 *
	 * @param int $userId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store($userId)
	{
		$user = $this->auth->getUser();

		if ($user && ($userId == $user->id))
		{
			if ($this->validator->validate(Input::all()))
			{
				$user->password = Input::get('password');
				$user->save();

				return Redirect::route('profile.index')->with('success_password_change', true);
			}

			return Redirect::route('profile.password.create', $user->id)->withErrors($this->validator->getErrors());
		}

		return Redirect::route('profile.index');
	}

}