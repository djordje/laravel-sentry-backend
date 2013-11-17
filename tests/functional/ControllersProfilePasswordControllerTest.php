<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Mockery as m;
use Djordje\LaravelSentryBackend\tests\helpers\TestCase;

class ControllersProfilePasswordControllerTest extends TestCase {

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
	protected $validator;

	public function setUp()
	{
		parent::setUp();

		$this->users = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Users');
		$this->auth = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Auth');
		$this->validator = m::mock('Djordje\LaravelSentryBackend\Services\Validators\PasswordUpdateValidator');

		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Users', $this->users);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Auth', $this->auth);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Validators\PasswordUpdateValidator', $this->validator);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testCreate()
	{
		$user = m::mock();
		$user->id = 1;

		$this->auth->shouldReceive('getUser')->andReturn($user);
		$this->route('get', 'profile.password.create', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);
	}

	public function testCreateWithoutUser()
	{
		$this->auth->shouldReceive('getUser')->andReturn(false);
		$this->route('get', 'profile.password.create', 1);
		$this->assertRedirectedToRoute('profile.index');
	}

	public function testStore()
	{
		$this->auth->shouldReceive('getUser')->once()->andReturn(false);
		$this->route('post', 'profile.password.store', 1);
		$this->assertRedirectedToRoute('profile.index');

		$user = m::mock();
		$user->id = 1;

		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->route('post', 'profile.password.store', 10);
		$this->assertRedirectedToRoute('profile.index');

		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->validator->shouldReceive('validate')->once()->andReturn(false);
		$this->validator->shouldReceive('getErrors')->once()->andReturn(array());
		$this->route('post', 'profile.password.store', 1);
		$this->assertRedirectedToRoute('profile.password.create', 1);
		$this->assertSessionHasErrors();

		$this->auth->shouldReceive('getUser')->once()->andReturn($user);
		$this->validator->shouldReceive('validate')->once()->andReturn(true);
		$user->shouldReceive('save')->once()->andReturn(true);
		$this->route('post', 'profile.password.store', 1);
		$this->assertRedirectedToRoute('profile.index');
		$this->assertSessionHas('success_password_change', true);
	}

}