<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Mockery as m;
use Djordje\LaravelSentryBackend\tests\helpers\TestCase;

class ControllersUsersControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $validator;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $users;

	public function setUp()
	{
		parent::setUp();

		$this->validator = m::mock('Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator');
		$this->users = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Users');

		$this->app->instance('Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator', $this->validator);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Users', $this->users);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testIndex()
	{
		$this->users->shouldReceive('findAll')->once()->andReturn(array());
		$this->route('get', 'users.index');
		$this->assertResponseOk();
		$this->assertViewHas('users', array());
	}

	public function testCreate()
	{
		$this->route('get', 'users.create');
		$this->assertResponseOk();
	}

	public function testStore()
	{
		$input = array('email' => '', 'password' => '', 'first_name' => '', 'last_name' => '');

		$this->validator->shouldReceive('validate')->with($input)->once()->andReturn(false);
		$this->validator->shouldReceive('getErrors')->once()->andReturn(array());
		$this->route('post', 'users.store', null, $input);
		$this->assertRedirectedToRoute('users.create');
		$this->assertSessionHasErrors();

		$input['email'] = 'existing@example.com';
		$input['password'] = '123456789abc';

		$this->validator->shouldReceive('validate')->with($input)->once()->andReturn(true);
		$this->users->shouldReceive('create')->with($input)->once()->andReturn(false);
		$this->users->shouldReceive('getError')->once()->andReturn('User exists');
		$this->route('post', 'users.store', null, $input);
		$this->assertRedirectedToRoute('users.create');
		$this->assertSessionHasErrors();

		$input['email'] = 'test@example.com';

		$this->validator->shouldReceive('validate')->with($input)->once()->andReturn(true);
		$this->users->shouldReceive('create')->with($input)->once()->andReturn(true);
		$this->route('post', 'users.store', null, $input);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testShow()
	{
		$user = m::mock();
		$user->id = 1;
		$user->email = 'test@example.com';
		$user->first_name = '';
		$user->last_name = '';
		$user->created_at = '2013-11-10 23:00:00';
		$user->updated_at  = '2013-11-10 23:00:00';
		$user->activated_at  = '2013-11-10 23:01:00';
		$user->last_login = '2013-11-10 23:01:00';
		$user->shouldReceive('getGroups')->once()->andReturn(array());

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->route('get', 'users.show', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);

		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'users.show', 10);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testEdit()
	{
		$user = m::mock();
		$user->id = 1;
		$user->email = 'test@example.com';
		$user->first_name = '';
		$user->last_name = '';

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->route('get', 'users.edit', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);

		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'users.edit', 10);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testUpdate()
	{
		$user = m::mock();
		$user->id = 1;
		$user->email = 'test@example.com';
		$user->first_name = '';
		$user->last_name = '';
		$user->shouldReceive('save')->once()->andReturn(true);

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->route('put', 'users.update', 1, array('first_name' => 'John'));
		$this->assertRedirectedToRoute('users.index');

		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('put', 'users.update', 10);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testDestroy()
	{
		$this->users->shouldReceive('delete')->with(1)->once()->andReturn(true);
		$this->route('delete', 'users.destroy', 1);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testActivate()
	{
		$user = m::mock();
		$user->shouldReceive('getActivationCode')->once()->andReturn('secretC0d3');
		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->users->shouldReceive('activate')->with(1, 'secretC0d3');
		$this->route('patch', 'users.activate', 1);
		$this->assertRedirectedToRoute('users.index');
	}

}