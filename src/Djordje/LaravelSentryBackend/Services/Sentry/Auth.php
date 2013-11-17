<?php namespace Djordje\LaravelSentryBackend\Services\Sentry;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Throttling\UserBannedException;

class Auth {

	/**
	 * Last auth's error.
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Get last auth's error.
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Attempt to authenticate user with given credentials.
	 *
	 * @param array $credentials
	 * @return bool
	 */
	public function auth(array $credentials)
	{
		$remember = (isset($credentials['remember'])) ? true : false;
		try
		{
			Sentry::authenticate(array(
				'email' => $credentials['email'],
				'password' => $credentials['password']
			), $remember);
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
		catch (WrongPasswordException $e)
		{
			$this->error = 'Wrong email or password, try again.';
			return false;
		}
		catch (UserNotFoundException $e)
		{
			$this->error = 'Wrong email or password, try again.';
			return false;
		}
		catch (UserNotActivatedException $e)
		{
			$this->error = 'This account has not been activated yet, please check your mailbox.';
			return false;
		}
		catch (UserSuspendedException $e)
		{
			$this->error = 'This account is suspended!';
			return false;
		}
		catch (UserBannedException $e)
		{
			$this->error = 'This account is banned!';
			return false;
		}

		return true;
	}

	/**
	 * Log out a user.
	 */
	public function logout()
	{
		Sentry::logout();
	}

	/**
	 * Check if user logged in and active.
	 *
	 * @return bool
	 */
	public function check()
	{
		if ( ! Sentry::check())
		{
			return false;
		}

		return true;
	}

	/**
	 * Get current user.
	 *
	 * @return \Cartalyst\Sentry\Users\UserInterface|false
	 */
	public function getUser()
	{
		try
		{
			$user = Sentry::getUser();
		}
		catch (UserNotFoundException $e)
		{
			return false;
		}

		return $user;
	}

}