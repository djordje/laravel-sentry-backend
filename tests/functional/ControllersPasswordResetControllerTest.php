<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Djordje\LaravelSentryBackend\tests\helpers\TestCase;

use Mockery as m;

class ControllersPasswordResetControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $users;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $reset;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $validator;

	public function setUp()
	{
		parent::setUp();

		$this->users = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Users');
		$this->reset = m::mock('Djordje\LaravelSentryBackend\Services\Notifiers\PasswordReset');
		$this->validator = m::mock('Djordje\LaravelSentryBackend\Services\Validators\PasswordResetValidator');

		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Users', $this->users);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Notifiers\PasswordReset', $this->reset);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Validators\PasswordResetValidator', $this->validator);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testCreate()
	{
		$this->route('get', 'password-reset.create');
		$this->assertResponseOk();
	}

	public function testStore()
	{
		$this->users->shouldReceive('findByCredentials')->once()->andReturn(false);
		$this->users->shouldReceive('getError')->once()->andReturn('Incorrect credentials');
		$this->route('post', 'password-reset.store');
		$this->assertSessionHasErrors();

		$user = m::mock();
		$this->users->shouldReceive('findByCredentials')->with(array('email' => 'test@example.com'))->once()->andReturn($user);
		$this->reset->shouldReceive('send')->with($user)->once()->andReturn(true);
		$this->route('post', 'password-reset.store', null, array('email' => 'test@example.com'));
		$this->assertRedirectedToRoute('password-reset.create');
		$this->assertSessionHas('password_reset_sent', true);
	}

	public function testEdit()
	{
		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'password-reset.edit', array(10, '123456789'));
		$this->assertRedirectedToRoute('password-reset.create');

		$user = m::mock();
		$user->id = 1;

		$user->shouldReceive('checkResetPasswordCode')->with('123456789')->once()->andReturn(false);
		$this->users->shouldReceive('findById')->once()->andReturn($user);
		$this->route('get', 'password-reset.edit', array(10, '123456789'));
		$this->assertRedirectedToRoute('password-reset.create');

		$user->shouldReceive('checkResetPasswordCode')->with('123456789')->once()->andReturn(true);
		$this->users->shouldReceive('findById')->once()->andReturn($user);
		$this->route('get', 'password-reset.edit', array(1, '123456789'));
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);
		$this->assertViewHas('code', '123456789');
	}

	public function testUpdate()
	{
		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('put', 'password-reset.update');
		$this->assertRedirectedToRoute('password-reset.create');

		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('put', 'password-reset.update', null, array('code' => '123456789'));
		$this->assertRedirectedToRoute('password-reset.create');

		$this->users->shouldReceive('findById')->once()->andReturn(true);
		$this->route('put', 'password-reset.update', null, array('id' => '1'));
		$this->assertRedirectedToRoute('password-reset.create');

		$user = m::mock();
		$user->id = 1;

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->validator->shouldReceive('validate')->once()->andReturn(false);
		$this->validator->shouldReceive('getErrors')->once()->andReturn(array());
		$this->route('put', 'password-reset.update', null, array('id' => '1', 'code' => '123456789'));
		$this->assertRedirectedToRoute('password-reset.edit', array(1, '123456789'));
		$this->assertSessionHasErrors();

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->validator->shouldReceive('validate')->once()->andReturn(true);
		$user->shouldReceive('checkResetPasswordCode')->with('123456789')->once()->andReturn(false);
		$this->route('put', 'password-reset.update', null, array('id' => '1', 'code' => '123456789'));
		$this->assertRedirectedToRoute('password-reset.create');

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->validator->shouldReceive('validate')->once()->andReturn(true);
		$user->shouldReceive('checkResetPasswordCode')->with('123456789')->once()->andReturn(true);
		$user->shouldReceive('attemptResetPassword')->with('123456789', 'newPassword')->once()->andReturn(true);
		$this->route('put', 'password-reset.update', null, array('id' => '1', 'code' => '123456789', 'password' => 'newPassword'));
		$this->assertRedirectedToRoute('session.create');
		$this->assertSessionHas('success_password_reset', true);
	}


}
