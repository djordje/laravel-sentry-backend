<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Config;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;

class SessionController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	public function __construct(Auth $auth)
	{
		$this->auth = $auth;
	}

	/**
	 * Show the form for creating session.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('laravel-sentry-backend::session.create');
	}

	/**
	 * Try to create session with provided credentials.
	 *
	 * @return Response
	 */
	public function store()
	{
		$credentials = array('email' => Input::get('email', null), 'password' => Input::get('password', null));

		if ($this->auth->auth($credentials))
		{
			return Redirect::route(Config::get('laravel-sentry-backend::login_redirect'));
		}

		return Redirect::route('session.create')->with(array('login_error' => $this->auth->getError()))->withInput();
	}

	/**
	 * Destroy session (logout).
	 *
	 * @return Redirect
	 */
	public function destroy()
	{
		$this->auth->logout();

		return Redirect::route(Config::get('laravel-sentry-backend::logout_redirect'));
	}

}
