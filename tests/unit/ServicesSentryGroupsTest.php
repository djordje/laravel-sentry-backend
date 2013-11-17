<?php namespace Djordje\LaravelSentryBackend\tests\unit;

use Mockery as m;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Djordje\LaravelSentryBackend\Services\Sentry\Groups;

class ServicesSentryGroupsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \Djordje\LaravelSentryBackend\Services\Sentry\Groups
	 */
	protected $groups;

	public function setUp()
	{
		$this->groups = new Groups;
	}

	public function tearDown()
	{
		m::close();
	}

	public function testCreate()
	{
		Sentry::shouldReceive('createGroup')->once()->andThrow('Cartalyst\Sentry\Groups\NameRequiredException');
		$this->assertFalse($this->groups->create(array()));

		Sentry::shouldReceive('createGroup')->once()->with(array('name' => 'Admin'))->andThrow('Cartalyst\Sentry\Groups\GroupExistsException');
		$this->assertFalse($this->groups->create(array('name' => 'Admin')));
		$this->assertNotNull($this->groups->getError());

		Sentry::shouldReceive('createGroup')->once()->with(m::hasKey('name'))->andReturn(true);
		$this->assertTrue($this->groups->create(array('name' => '')));
		$this->assertNotNull($this->groups->getError());
	}

	public function testFindAll()
	{
		Sentry::shouldReceive('findAllGroups')->once()->andReturnNull();
		$this->assertNull($this->groups->findAll());
	}

	public function testFindById()
	{
		Sentry::shouldReceive('findGroupById')->with(1)->once()->andReturn(array());
		$this->assertEquals(array(), $this->groups->findById(1));

		Sentry::shouldReceive('findGroupById')->once()->andThrow('Cartalyst\Sentry\Groups\GroupNotFoundException');
		$this->assertFalse($this->groups->findById(10));
		$this->assertNotNull($this->groups->getError());
	}

	public function testFindByName()
	{
		Sentry::shouldReceive('findGroupByName')->with('Admin')->once()->andReturn(array());
		$this->assertEquals(array(), $this->groups->findByName('Admin'));

		Sentry::shouldReceive('findGroupByName')->once()->andThrow('Cartalyst\Sentry\Groups\GroupNotFoundException');
		$this->assertFalse($this->groups->findByName('Moderator'));
		$this->assertNotNull($this->groups->getError());
	}

	public function testUpdate()
	{
		$obj = (object) array('name' => 'Admin', 'permissions' => '');
		$mock = m::mock($obj);
		$mock->shouldReceive('save')->once()->andReturn(true);

		Sentry::shouldReceive('findGroupById')->with(1)->once()->andReturn($mock);
		$this->assertTrue($this->groups->update(1, array('name' => 'Root', 'permissions' => '{"user.create":1}')));

		Sentry::shouldReceive('findGroupById')->once()->andThrow('Cartalyst\Sentry\Groups\GroupExistsException');
		$this->assertFalse($this->groups->update(1, array('name' => 'Member')));

		Sentry::shouldReceive('findGroupById')->once()->andThrow('Cartalyst\Sentry\Groups\GroupNotFoundException');
		$this->assertFalse($this->groups->update(10, array()));
	}

	public function testDelete()
	{
		$mock = m::mock('Group');
		$mock->shouldReceive('delete')->once()->andReturn(true);
		Sentry::shouldReceive('findGroupById')->with(1)->once()->andReturn($mock);
		$this->assertTrue($this->groups->delete(1));

		Sentry::shouldReceive('findGroupById')->once()->andThrow('Cartalyst\Sentry\Groups\GroupNotFoundException');
		$this->assertFalse($this->groups->delete(10));
	}

}