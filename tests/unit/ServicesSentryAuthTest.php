<?php namespace Djordje\LaravelSentryBackend\tests\unit;

use Mockery as m;
use Djordje\LaravelSentryBackend\Services\Sentry\Auth;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

class ServicesSentryAuthTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Auth
	 */
	protected $auth;

	public function setUp()
	{
		$this->auth = new Auth;
	}

	public function tearDown()
	{
		m::close();
	}

	public function testAuth()
	{
		$credentials = array('email' => 'test@example.com', 'password' => '123456789abc');

		Sentry::shouldReceive('authenticate')->with($credentials, false)->once();
		$this->assertTrue($this->auth->auth($credentials));

		$credentialsRemember = $credentials + array('remember' => '1');
		Sentry::shouldReceive('authenticate')->with($credentials, true)->once();
		$this->assertTrue($this->auth->auth($credentialsRemember));

		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Users\UserNotActivatedException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Throttling\UserSuspendedException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Throttling\UserBannedException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Users\WrongPasswordException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		$credentials['password'] = '';
		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Users\PasswordRequiredException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());

		$credentials['email'] = '';
		Sentry::shouldReceive('authenticate')->once()->andThrow('Cartalyst\Sentry\Users\LoginRequiredException');
		$this->assertFalse($this->auth->auth($credentials));
		$this->assertNotNull($this->auth->getError());
	}

	public function testLogout()
	{
		Sentry::shouldReceive('logout')->once()->withNoArgs();
		$this->assertNull($this->auth->logout());
	}

	public function testCheck()
	{
		Sentry::shouldReceive('check')->once()->withNoArgs()->andReturn(true);
		$this->assertTrue($this->auth->check());

		Sentry::shouldReceive('check')->once()->withNoArgs()->andReturn(false);
		$this->assertFalse($this->auth->check());
	}

	public function testGetUser()
	{
		Sentry::shouldReceive('getUser')->once()->withNoArgs()->andReturn(array());
		$this->assertEquals(array(), $this->auth->getUser());

		Sentry::shouldReceive('getUser')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->auth->getUser());
	}

}