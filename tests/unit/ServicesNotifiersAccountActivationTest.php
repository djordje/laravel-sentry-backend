<?php namespace Djordje\LaravelSentryBackend\tests\unit;

use Mockery as m;
use Illuminate\Support\Facades\Mail;
use Djordje\LaravelSentryBackend\Services\Notifiers\AccountActivation;

class ServicesNotifiersAccountActivationTest extends \PHPUnit_Framework_TestCase {

	protected $activation;

	public function setUp()
	{
		$this->activation = new AccountActivation();
	}

	public function tearDown()
	{
		m::close();
	}

	public function testSend()
	{
		$user = m::mock();
		$user->shouldReceive('getActivationCode')->once()->andReturn('secretC0d3');

		Mail::shouldReceive('queue')->once()->with(
			m::type('array'),
			array(
				'user' => $user,
				'code' => 'secretC0d3'
			),
			m::type('closure')
		);

		$this->activation->send($user);
	}

}