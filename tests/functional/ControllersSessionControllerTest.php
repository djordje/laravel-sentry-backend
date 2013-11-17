<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Djordje\LaravelSentryBackend\tests\helpers\TestCase;
use Mockery as m;

class ControllersSessionControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $auth;

	public function setUp()
	{
		parent::setUp();
		$this->auth = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Auth');
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Auth', $this->auth);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testCreate()
	{
		$this->route('get', 'session.create');
		$this->assertResponseOk();
	}

	public function testStore()
	{
		$redirect = $this->app['config']->get('laravel-sentry-backend::login_redirect');
		$credentials = array('email' => 'test@exmample.com', 'password' => '123456789abc');
		$this->auth->shouldReceive('auth')->with($credentials)->once()->andReturn(true);
		$this->route('post', 'session.create', null, $credentials);
		$this->assertRedirectedToRoute($redirect);

		$this->auth->shouldReceive('auth')->with(array('email' => null, 'password' => null))->once()->andReturn(false);
		$this->auth->shouldReceive('getError')->once()->andReturn('Login error');
		$this->route('post', 'session.create');
		$this->assertRedirectedToRoute('session.create');
		$this->assertSessionHas('login_error', 'Login error');
	}

	public function testDestroy()
	{
		$redirect = $this->app['config']->get('laravel-sentry-backend::logout_redirect');
		$this->auth->shouldReceive('logout')->once()->withNoArgs();
		$this->route('delete', 'session.destroy');
		$this->assertRedirectedToRoute($redirect);
	}

}