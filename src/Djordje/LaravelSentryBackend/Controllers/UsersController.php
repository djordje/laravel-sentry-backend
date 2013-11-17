<?php namespace Djordje\LaravelSentryBackend\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controllers\Controller;
use Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;

class UsersController extends Controller {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator
	 */
	protected $validator;

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	public function __construct(UserCreateValidator $validator, Users $users)
	{
		$this->validator = $validator;
		$this->users = $users;
	}

	/**
	 * Display all users.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = $this->users->findAll();

		return View::make('laravel-sentry-backend::users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new user.
	 *
	 * @return Response
	 */
	public function create()
	{
        return View::make('laravel-sentry-backend::users.create');
	}

	/**
	 * Store a newly created user in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		if ( ! $this->validator->validate(Input::all()))
		{
			return Redirect::route('users.create')->withErrors($this->validator->getErrors())->withInput();
		}

		$user = array(
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			'first_name' => Input::get('first_name'),
			'last_name' => Input::get('last_name')
		);

		if ($user = $this->users->create($user))
		{
			return Redirect::route('users.index');
		}

		return Redirect::route('users.create')->withErrors(array('error' => $this->users->getError()));
	}

	/**
	 * Display the specified user.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function show($id)
	{
		if ($user = $this->users->findById($id))
		{
			return View::make('laravel-sentry-backend::users.show', compact('user'));
		}

		return Redirect::route('users.index');
	}

	/**
	 * Show the form for editing the specified user.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function edit($id)
	{
		if ($user = $this->users->findById($id))
		{
			return View::make('laravel-sentry-backend::users.edit', compact('user'));
		}

		return Redirect::route('users.index');
	}

	/**
	 * Update the specified user in storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function update($id)
	{
		if ($user = $this->users->findById($id))
		{
			$user->email = Input::get('email');
			$user->first_name = Input::get('first_name');
			$user->last_name = Input::get('last_name');

			$user->save();

		}

		return Redirect::route('users.index');
	}

	/**
	 * Remove the specified user from storage.
	 *
	 * @param int $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->users->delete($id);

		return Redirect::route('users.index');
	}

	/**
	 * Activate the specified user in storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function activate($id)
	{
		$this->users->activate($id, $this->users->findById($id)->getActivationCode());

		return Redirect::route('users.index');
	}

}
