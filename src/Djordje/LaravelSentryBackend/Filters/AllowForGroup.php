<?php namespace Djordje\LaravelSentryBackend\Filters;

use App;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;
use Djordje\LaravelSentryBackend\Services\Sentry\Groups;

class AllowForGroup {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Groups
	 */
	protected $groups;

	public function __construct(Auth $auth, Groups $groups)
	{
		$this->auth = $auth;
		$this->groups = $groups;
	}

	public function filter($route, $request, $group)
	{
		$group = (is_int($group)) ? $this->groups->findById($group) : $this->groups->findByName($group);

		if ($group && ( ! $this->auth->check() || ! $this->auth->getUser()->inGroup($group)))
		{
			return App::abort(401, 'You are not authorized.');
		}
	}

}