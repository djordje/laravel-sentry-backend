<?php namespace Djordje\LaravelSentryBackend\Services\Sentry;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;

class Users {

	/**
	 * Last user's error
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Get lats user's error
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Return array of users
	 *
	 * @return array
	 */
	public function findAll()
	{
		return Sentry::findAllUsers();
	}

	/**
	 * Return user found by ID.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function findById($id) {
		try
		{
			$user = Sentry::findUserById($id);
		}
		catch (UserNotFoundException $e)
		{
			$this->error = 'User was not found.';
			return false;
		}

		return $user;
	}

	/**
	 * Return user found by given credentials.
	 *
	 * @param array $credentials
	 * @return bool
	 */
	public function findByCredentials(array $credentials)
	{
		try
		{
			$user = Sentry::findUserByCredentials($credentials);
		}
		catch (UserNotFoundException $e)
		{
			$this->error = 'Cant find user with given credentials.';
			return false;
		}

		return $user;
	}

	/**
	 * Find user by ID and attempt activation with passed code.
	 *
	 * @param int $id
	 * @param string $code
	 * @return bool
	 */
	public function activate($id, $code)
	{
		try
		{
			$user = Sentry::findUserById($id);

			if ( ! $user->attemptActivation($code))
			{
				$this->error = 'Activation attempt failed.';
				return false;
			}
		}
		catch (UserNotFoundException $e)
		{
			$this->error = 'User was not found.';
			return false;
		}
		catch (UserAlreadyActivatedException $e)
		{
			$this->error = 'User is already activated.';
			return false;
		}

		return true;
	}

	/**
	 * Create user with given credentials
	 *
	 * @param array $user
	 * @return \Cartalyst\Sentry\Users\UserInterface|false
	 */
	public function create(array $user)
	{
		try
		{
			$user = Sentry::createUser($user);
		}
		catch (LoginRequiredException $e)
		{
			$this->error = 'Email field is required.';
			return false;
		}
		catch (PasswordRequiredException $e)
		{
			$this->error = 'Password field is required.';
			return false;
		}
		catch (UserExistsException $e)
		{
			$this->error = 'User with this login already exists.';
			return false;
		}
		catch (GroupNotFoundException $e)
		{
			$this->error = 'Group was not found.';
			return false;
		}

		return $user;
	}

	/**
	 * Add user to group.
	 *
	 * @param \Cartalyst\Sentry\Users\UserInterface|int $user
	 * @param \Cartalyst\Sentry\Groups\GroupInterface $group
	 * @return bool
	 */
	public function addGroup($user, $group)
	{
		if (is_int($user))
		{
			if ( ! $user = $this->findById($user))
			{
				return false;
			}

		}
		return $user->addGroup($group);
	}

	/**
	 * Remove user from group.
	 *
	 * @param \Cartalyst\Sentry\Users\UserInterface|int $user
	 * @param \Cartalyst\Sentry\Groups\GroupInterface $group
	 * @return bool
	 */
	public function removeGroup($user, $group)
	{
		if (is_int($user))
		{
			if ( ! $user = $this->findById($user))
			{
				return false;
			}
		}

		return $user->removeGroup($group);
	}

	/**
	 * Remove user by ID.
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete($id)
	{
		try
		{
			$user = Sentry::findUserById($id);
			$user->delete();
		}
		catch (UserNotFoundException $e)
		{
			$this->error = 'User was not found.';
			return false;
		}

		return true;
	}

}