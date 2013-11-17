<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;
use Djordje\LaravelSentryBackend\Services\Sentry\Groups;

class UserGroupsController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Groups
	 */
	protected $groups;

	public function __construct(Users $users, Groups $groups)
	{
		$this->users = $users;
		$this->groups = $groups;
	}

	/**
	 * Show a list of groups that user belongs to.
	 *
	 * @param int $userId
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function index($userId)
	{
		if ($user = $this->users->findById($userId))
		{
			$userGroups = $user->getGroups();

            return View::make('laravel-sentry-backend::usergroups.index', compact('user', 'userGroups'));
		}

		return Redirect::route('users.index');
	}

	/**
	 * Show list of available groups for current user.
	 *
	 * @param int $userId
	 * @return \Illuminate\View\View
	 */
	public function create($userId)
	{
		$user = $this->users->findById($userId);
		$groups = $this->groups->findAll();
		$userGroups = $user->getGroups()->lists('id');

		foreach ($groups as $i => $group)
		{
			if (in_array($group->id, $userGroups))
			{
				unset($groups[$i]);
			}
		}

        return View::make('laravel-sentry-backend::usergroups.create', compact('user', 'groups'));
	}

	/**
	 * Add user to a group.
	 *
	 * @param int $userId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store($userId)
	{
		$user = $this->users->findById($userId);
		$group = $this->groups->findById(Input::get('group'));

		if ($user && $group)
		{
			$this->users->addGroup($user, $group);
		}

		return Redirect::route('users.groups.index', $userId);
	}

	/**
	 * Remove user from a group.
	 *
	 * @param int $userId
	 * @param int $groupId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($userId, $groupId)
	{
		$user = $this->users->findById($userId);
		$group = $this->groups->findById($groupId);

		$this->users->removeGroup($user, $group);

		return Redirect::route('users.groups.index', $userId);
	}

}
