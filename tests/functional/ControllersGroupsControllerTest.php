<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Djordje\LaravelSentryBackend\tests\helpers\TestCase;
use Mockery as m;

class ControllersGroupsControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $groups;

	public function setUp()
	{
		parent::setUp();
		$this->groups = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Groups');
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Groups', $this->groups);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testIndex()
	{
		$this->groups->shouldReceive('findAll')->once()->andReturn(array());
		$this->route('get', 'groups.index');
		$this->assertResponseOk();
		$this->assertViewHas('groups', array());
	}

	public function testCreate()
	{
		$this->route('get', 'groups.create');
		$this->assertResponseOk();
	}

	public function testStore()
	{
		$this->groups->shouldReceive('create')->once()->andReturn(true);
		$this->route('post', 'groups.store', array(), array('name' => 'members'));
		$this->assertRedirectedToRoute('groups.index');

		$this->groups->shouldReceive('create')->once()->andReturn(false);
		$this->groups->shouldReceive('getError')->once()->andReturn('Error');
		$this->route('post', 'groups.store', array(), array('name' => 'members'));
		$this->assertRedirectedToRoute('groups.create');
		$this->assertSessionHasErrors();
	}

	public function testShow()
	{
		$group = (object) array('name' => 'members', 'permissions' => array());
		$this->groups->shouldReceive('findById')->with(1)->once()->andReturn($group);
		$this->route('get', 'groups.show', array(1));
		$this->assertResponseOk();
		$this->assertViewHas('group', $group);

		$this->groups->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'groups.show', array(10));
		$this->assertRedirectedToRoute('groups.index');
	}

	public function testEdit()
	{
		$group = (object) array('id' => 1, 'name' => 'members', 'permissions' => array());
		$this->groups->shouldReceive('findById')->with(1)->once()->andReturn($group);
		$this->route('get', 'groups.edit', 1);
		$this->assertResponseOk();
		$this->assertViewHas('group', $group);

		$this->groups->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'groups.edit', 10);
		$this->assertRedirectedToRoute('groups.index');
	}

	public function testUpdate()
	{
		$input = array('name' => 'users', 'permissions' => array());
		$this->groups->shouldReceive('update')->with(1, $input)->once()->andReturn(true);
		$this->route('put', 'groups.update', 1, $input);
		$this->assertRedirectedToRoute('groups.index');

		$this->groups->shouldReceive('update')->once()->andReturn(false);
		$this->groups->shouldReceive('getError')->once()->andReturn('Not updated error');
		$this->route('put', 'groups.update', 10, $input);
		$this->assertRedirectedToRoute('groups.index');
		$this->assertSessionHas('not_updated', 'Not updated error');
	}

	public function testDestroy()
	{
		$this->groups->shouldReceive('delete')->with(1)->once()->andReturn(true);
		$this->route('delete', 'groups.destroy', 1);
		$this->assertRedirectedToRoute('groups.index');

		$this->groups->shouldReceive('delete')->once()->andReturn(false);
		$this->route('delete', 'groups.destroy', 10);
		$this->assertRedirectedToRoute('groups.index');
	}

}