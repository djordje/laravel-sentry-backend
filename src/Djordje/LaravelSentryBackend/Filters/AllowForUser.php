<?php namespace Djordje\LaravelSentryBackend\Filters;

use App;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;

class AllowForUser {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	public function __construct(Auth $auth)
	{
		$this->auth = $auth;
	}

	public function filter()
	{
		if ( ! $this->auth->check())
		{
			return App::abort(401, 'You are not authorized.');
		}
	}

}