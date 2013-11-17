<?php namespace Djordje\LaravelSentryBackend\tests\unit;

use Mockery as m;
use Djordje\LaravelSentryBackend\Services\Sentry\Users;
use Cartalyst\Sentry\Facades\Laravel\Sentry;

class ServicesSentryUsersTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Users
	 */
	protected $users;

	public function setUp()
	{
		$this->users = new Users;
	}

	public function tearDown()
	{
		m::close();
	}

	public function testFindAll()
	{
		Sentry::shouldReceive('findAllUsers')->once()->andReturn(array());
		$this->assertEquals(array(), $this->users->findAll());
	}

	public function testFindById()
	{
		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn(true);
		$this->assertTrue($this->users->findById(1));

		Sentry::shouldReceive('findUserById')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->users->findById(10));
		$this->assertNotNull($this->users->getError());
	}

	public function testFindByCredentials()
	{
		$user = m::mock();
		$user->email = 'test@example.com';
		$credentials = array('email' => 'test@example.com');
		Sentry::shouldReceive('findUserByCredentials')->with($credentials)->once()->andReturn($user);
		$this->assertEquals($user, $this->users->findByCredentials($credentials));

		Sentry::shouldReceive('findUserByCredentials')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->users->findByCredentials(array('email' => '')));
		$this->assertNotNull($this->users->getError());
	}

	public function testActivate()
	{
		$code = '123456789abc';
		$mock = m::mock();

		$mock->shouldReceive('attemptActivation')->with($code)->once()->andReturn(true);
		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn($mock);
		$this->assertTrue($this->users->activate(1, $code));

		$mock->shouldReceive('attemptActivation')->once()->andReturn(false);
		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn($mock);
		$this->assertFalse($this->users->activate(1, $code));
		$this->assertNotNull($this->users->getError());

		Sentry::shouldReceive('findUserById')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->users->activate(10, $code));
		$this->assertNotNull($this->users->getError());

		Sentry::shouldReceive('findUserById')->once()->andThrow('Cartalyst\Sentry\Users\UserAlreadyActivatedException');
		$this->assertFalse($this->users->activate(2, $code));
		$this->assertNotNull($this->users->getError());
	}

	public function testCreate()
	{
		$credentials = array('email' => 'user@example.com', 'password' => '123456789');

		Sentry::shouldReceive('createUser')->with($credentials)->once()->andReturn(array());
		$this->assertEquals(array(), $this->users->create($credentials));

		Sentry::shouldReceive('createUser')->once()->andThrow('Cartalyst\Sentry\Users\UserExistsException');
		$this->assertFalse($this->users->create($credentials));
		$this->assertNotNull($this->users->getError());

		Sentry::shouldReceive('createUser')->once()->andThrow('Cartalyst\Sentry\Users\UserExistsException');
		$this->assertFalse($this->users->create($credentials));
		$this->assertNotNull($this->users->getError());

		Sentry::shouldReceive('createUser')->once()->andThrow('Cartalyst\Sentry\Groups\GroupNotFoundException');
		$this->assertFalse($this->users->create($credentials));
		$this->assertNotNull($this->users->getError());

		unset($credentials['password']);
		Sentry::shouldReceive('createUser')->once()->andThrow('Cartalyst\Sentry\Users\PasswordRequiredException');
		$this->assertFalse($this->users->create($credentials));
		$this->assertNotNull($this->users->getError());

		unset($credentials['email']);
		Sentry::shouldReceive('createUser')->once()->andThrow('Cartalyst\Sentry\Users\LoginRequiredException');
		$this->assertFalse($this->users->create($credentials));
		$this->assertNotNull($this->users->getError());
	}

	public function testAddGroup()
	{
		$group = m::mock();
		$mock = m::mock();

		$mock->shouldReceive('addGroup')->with($group)->once()->andReturn(true);
		$this->assertTrue($this->users->addGroup($mock, $group));

		$mock->shouldReceive('addGroup')->with($group)->once()->andReturn(false);
		$this->assertFalse($this->users->addGroup($mock, $group));

		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn($mock);
		$mock->shouldReceive('addGroup')->with($group)->once()->andReturn(true);
		$this->assertTrue($this->users->addGroup(1, $group));

		Sentry::shouldReceive('findUserById')->once()->andReturn(false);
		$this->assertFalse($this->users->addGroup(10, $group));
	}

	public function testRemoveGroup()
	{
		$group = m::mock();
		$mock = m::mock();

		$mock->shouldReceive('removeGroup')->with($group)->once()->andReturn(true);
		$this->assertTrue($this->users->removeGroup($mock, $group));

		$mock->shouldReceive('removeGroup')->with($group)->once()->andReturn(false);
		$this->assertFalse($this->users->removeGroup($mock, $group));

		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn($mock);
		$mock->shouldReceive('removeGroup')->with($group)->once()->andReturn(true);
		$this->assertTrue($this->users->removeGroup(1, $group));

		Sentry::shouldReceive('findUserById')->once()->andReturn(false);
		$this->assertFalse($this->users->removeGroup(10, $group));
	}

	public function testDelete()
	{
		$mock = m::mock();

		Sentry::shouldReceive('findUserById')->with(1)->once()->andReturn($mock);
		$mock->shouldReceive('delete')->once()->andReturn(true);
		$this->assertTrue($this->users->delete(1));

		Sentry::shouldReceive('findUserById')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->assertFalse($this->users->delete(10));
		$this->assertNotNull($this->users->getError());
	}

}