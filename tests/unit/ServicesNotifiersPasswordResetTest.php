<?php namespace Djordje\LaravelSentryBackend\tests\unit;

use Mockery as m;
use Illuminate\Support\Facades\Mail;
use Djordje\LaravelSentryBackend\Services\Notifiers\PasswordReset;

class ServicesNotifiersPasswordResetTest extends \PHPUnit_Framework_TestCase {

	protected $reset;

	public function setUp()
	{
		$this->reset = new PasswordReset();
	}

	public function tearDown()
	{
		m::close();
	}

	public function testSend()
	{
		$user = m::mock();
		$user->shouldReceive('getResetPasswordCode')->once()->andReturn('123456789');

		Mail::shouldReceive('queue')->once()->with(
			m::type('array'),
			array(
				'user' => $user,
				'code' => '123456789'
			),
			m::type('closure')
		);

		$this->reset->send($user);
	}

}