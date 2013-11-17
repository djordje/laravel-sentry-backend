<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Djordje\LaravelSentryBackend\tests\helpers\TestCase;
use Mockery as m;

class ControllerProfileControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $validator;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $users;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $auth;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $activation;

	public function setUp()
	{
		parent::setUp();

		$this->validator = m::mock('Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator');
		$this->users = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Users');
		$this->auth = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Auth');
		$this->activation = m::mock('Djordje\LaravelSentryBackend\Services\Notifiers\AccountActivation');

		$this->app->instance('Djordje\LaravelSentryBackend\Services\Validators\UserCreateValidator', $this->validator);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Users', $this->users);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Auth', $this->auth);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Notifiers\AccountActivation', $this->activation);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testIndex()
	{
		$user = m::mock();
		$user->id = 1;
		$user->email = 'test@example.com';
		$user->first_name = '';
		$user->last_name = '';
		$user->shouldReceive('getGroups')->once()->andReturn(array());
		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->route('get', 'profile.index');
		$this->assertResponseOk();

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->route('get', 'profile.index');
		$this->assertRedirectedToRoute('session.create');
	}

	public function testCreate()
	{
		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->route('get', 'profile.create');
		$this->assertRedirectedToRoute('profile.index');

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->route('get', 'profile.create');
		$this->assertResponseOk();
	}

	/**
	 * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function testDisabledCreate()
	{
		$this->app['config']->set('laravel-sentry-backend::enable_registrations', false);
		$this->route('get', 'profile.create');
	}

	public function testStore()
	{
		$input = array('email' => '', 'password' => '');

		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->route('post', 'profile.store');
		$this->assertRedirectedToRoute('profile.index');

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->validator->shouldReceive('validate')->once()->with($input)->andReturn(false);
		$this->validator->shouldReceive('getErrors')->once()->andReturn(array());
		$this->route('post', 'profile.store', null, $input);
		$this->assertRedirectedToRoute('profile.create');
		$this->assertSessionHasErrors();

		$userInput = $input + array('first_name' => '', 'last_name' => '');

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->validator->shouldReceive('validate')->once()->with($input)->andReturn(true);
		$this->users->shouldReceive('create')->with($userInput)->once()->andReturn(false);
		$this->users->shouldReceive('getError')->once()->andReturn(array());
		$this->route('post', 'profile.store', null, $input);
		$this->assertRedirectedToRoute('profile.create');
		$this->assertSessionHasErrors();

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->validator->shouldReceive('validate')->once()->with($input)->andReturn(true);
		$this->users->shouldReceive('create')->with($userInput)->once()->andReturn(true);
		$this->activation->shouldReceive('send')->with($userInput)->once();
		$this->route('post', 'profile.store', null, $input);
		$this->assertRedirectedToRoute('session.create');
		$this->assertSessionHas('success_registration', true);
	}

	/**
	 * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function testDisabledStore()
	{
		$this->app['config']->set('laravel-sentry-backend::enable_registrations', false);
		$this->route('post', 'profile.store');
	}

	public function testEdit()
	{
		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->route('get', 'profile.edit', 1);
		$this->assertRedirectedToRoute('session.create');

		$user = (object) array('id' => 1);

		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->route('get', 'profile.edit', 10);
		$this->assertRedirectedToRoute('profile.index');

		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->route('get', 'profile.edit', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);
	}

	public function testUpdate()
	{
		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->route('put', 'profile.update', 1);
		$this->assertRedirectedToRoute('session.create');

		$user = (object) array('id' => 1);

		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->route('put', 'profile.update', 10);
		$this->assertRedirectedToRoute('profile.index');

		$mock = m::mock();
		$mock->id = $user->id;
		$mock->shouldReceive('save')->once();

		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->auth->shouldReceive('getUser')->once()->andReturn($mock);
		$this->route('put', 'profile.update', 1);
		$this->assertRedirectedToRoute('profile.index');
	}

	public function testActivate()
	{
		$this->auth->shouldReceive('check')->once()->andReturn(true);
		$this->route('get', 'profile.activate', array(1, 'randomC0d3'));
		$this->assertRedirectedToRoute('profile.index');

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->users->shouldReceive('activate')->with(1, 'randomC0d3')->once()->andReturn(false);
		$this->users->shouldReceive('getError')->once()->andReturn('Error activation');
		$this->route('get', 'profile.activate', array(1, 'randomC0d3'));
		$this->assertRedirectedToRoute('session.create');
		$this->assertSessionHas('error_activation', 'Error activation');

		$this->auth->shouldReceive('check')->once()->andReturn(false);
		$this->users->shouldReceive('activate')->with(1, 'randomC0d3')->once()->andReturn(true);
		$this->route('get', 'profile.activate', array(1, 'randomC0d3'));
		$this->assertRedirectedToRoute('session.create');
		$this->assertSessionHas('success_activation', true);
	}

}