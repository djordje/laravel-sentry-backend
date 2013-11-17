<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Sentry\Groups;

class GroupsController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Groups
	 */
	protected $groups;

	public function __construct(Groups $groups)
	{
		$this->groups = $groups;
	}

	/**
	 * Display a listing of the groups.
	 */
	public function index()
	{
		$groups = $this->groups->findAll();
        return View::make('laravel-sentry-backend::groups.index', compact('groups'));
	}

	/**
	 * Show the form for creating a new group.
	 */
	public function create()
	{
        return View::make('laravel-sentry-backend::groups.create');
	}

	/**
	 * Store a newly created group in storage.
	 */
	public function store()
	{
		$input = array('name' => Input::get('name'), 'permissions' => Input::get('permissions', array()));

		if ($this->groups->create($input))
		{
			return Redirect::route('groups.index');
		}

		return Redirect::route('groups.create')->withErrors(array('name' => $this->groups->getError()))->withInput();
	}

	/**
	 * Display the specified group.
	 *
	 * @param int $id
	 */
	public function show($id)
	{
		if ($group = $this->groups->findById($id))
		{
			return View::make('laravel-sentry-backend::groups.show', compact('group'));
		}

		return Redirect::route('groups.index');
	}

	/**
	 * Show the form for editing the specified group.
	 *
	 * @param int $id
	 */
	public function edit($id)
	{
		if ($group = $this->groups->findById($id))
		{
			return View::make('laravel-sentry-backend::groups.edit', compact('group'));
		}

		return Redirect::route('groups.index');
	}

	/**
	 * Update the specified group in storage.
	 *
	 * @param int $id
	 */
	public function update($id)
	{
		$input = array('name' => Input::get('name'), 'permissions' => Input::get('permissions', array()));

		if ($this->groups->update($id, $input))
		{
			return Redirect::route('groups.index');
		}

		return Redirect::route('groups.index')->with(array('not_updated' => $this->groups->getError()));
	}

	/**
	 * Remove the specified group from storage.
	 *
	 * @param int $id
	 */
	public function destroy($id)
	{
		$this->groups->delete($id);

		return Redirect::route('groups.index');
	}

}
