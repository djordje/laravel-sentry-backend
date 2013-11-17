<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;
use Djordje\LaravelSentryBackend\Services\Notifiers\AccountActivation;

class ProfileController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator
	 */
	protected $validator;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Notifiers\AccountActivation
	 */
	protected $activation;

	public function __construct(UserCreateValidator $validator, Users $users, Auth $auth, AccountActivation $activation)
	{
		$this->validator = $validator;
		$this->users = $users;
		$this->auth = $auth;
		$this->activation = $activation;
	}

	/**
	 * Display a profile of the user.
	 */
	public function index()
	{
		if ( ! $this->auth->check())
		{
			return Redirect::route('session.create');
		}

		$user = $this->auth->getUser();

        return View::make('laravel-sentry-backend::profile.index', compact('user'));
	}

	/**
	 * If registration enabled show the form for registering a new user.
	 */
	public function create()
	{
		if ( ! Config::get('laravel-sentry-backend::enable_registrations'))
		{
			return App::abort(404);
		}

		if ($this->auth->check())
		{
			return Redirect::route('profile.index');
		}

        return View::make('laravel-sentry-backend::profile.create');
	}

	/**
	 * If registration enabled store a newly registered user in storage.
	 */
	public function store()
	{
		if ( ! Config::get('laravel-sentry-backend::enable_registrations'))
		{
			return App::abort(404);
		}

		if ($this->auth->check())
		{
			return Redirect::route('profile.index');
		}

		if ( ! $this->validator->validate(Input::all()))
		{
			return Redirect::route('profile.create')->withErrors($this->validator->getErrors())->withInput();
		}

		$user = array(
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'first_name' => Input::get('first_name'),
			'last_name' => Input::get('last_name')
		);

		if ($user = $this->users->create($user))
		{
			$this->activation->send($user);

			return Redirect::route('session.create')->with('success_registration', true);
		}

		return Redirect::route('profile.create')->withErrors(array('error' => $this->users->getError()));
	}

	/**
	 * Show the form for editing the user profile.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		if ( ! $this->auth->check())
		{
			return Redirect::route('session.create');
		}

		$user = $this->auth->getUser();

		if ($id != $user->id)
		{
			return Redirect::route('profile.index');
		}

        return View::make('laravel-sentry-backend::profile.edit', compact('user'));
	}

	/**
	 * Update the user profile in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id)
	{
		if ( ! $this->auth->check())
		{
			return Redirect::route('session.create');
		}

		$user = $this->auth->getUser();

		if ($id == $user->id)
		{
			$user->first_name = Input::get('first_name');
			$user->last_name = Input::get('last_name');

			$user->save();
		}

		return Redirect::route('profile.index');
	}

	/**
	 * Attempt to activate user with code.
	 *
	 * @param int $id
	 * @param string $code
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function activate($id, $code)
	{
		if ($this->auth->check())
		{
			return Redirect::route('profile.index');
		}

		if ($this->users->activate($id, $code))
		{
			return Redirect::route('session.create')->with('success_activation', true);
		}

		return Redirect::route('session.create')->with('error_activation', $this->users->getError());
	}

}
