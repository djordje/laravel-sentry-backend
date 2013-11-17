<?php namespace Djordje\LaravelSentryBackend\tests\functional;

use Djordje\LaravelSentryBackend\tests\helpers\TestCase;
use Mockery as m;

class ControllersUserGroupsControllerTest extends TestCase {

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $groups;

	/**
	 * @var \Mockery\MockInterface
	 */
	protected $users;

	public function setUp()
	{
		parent::setUp();

		$this->groups = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Groups');
		$this->users = m::mock('Djordje\LaravelSentryBackend\Services\Sentry\Users');

		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Groups', $this->groups);
		$this->app->instance('Djordje\LaravelSentryBackend\Services\Sentry\Users', $this->users);
	}

	public function tearDown()
	{
		m::close();
	}

	public function testIndex()
	{
		$user = m::mock();
		$user->shouldReceive('getGroups')->withNoArgs()->once()->andReturn(array())->andSet('id', 1)->andSet('email', 'test@example.com');
		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->route('get', 'users.groups.index', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);
		$this->assertViewHas('userGroups', array());

		$this->users->shouldReceive('findById')->once()->andReturn(false);
		$this->route('get', 'users.groups.index', 10);
		$this->assertRedirectedToRoute('users.index');
	}

	public function testCreate()
	{
		$user = m::mock();
		$user->id = 1;
		$user->email = 'test@example.com';
		$userGroups = m::mock();
		$groups = array(
			(object) array('id' => 1, 'name' => 'Admins'),
			(object) array('id' => 2, 'name' => 'Managers'),
			(object) array('id' => 3, 'name' => 'Members')
		);

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->groups->shouldReceive('findAll')->once()->andReturn($groups);

		$userGroups->shouldReceive('lists')->with('id')->once()->andReturn(array(1));
		$user->shouldReceive('getGroups')->once()->andReturn($userGroups);

		unset($groups[0]);
		$this->route('get', 'users.groups.create', 1);
		$this->assertResponseOk();
		$this->assertViewHas('user', $user);
		$this->assertViewHas('groups', $groups);
	}

	public function testStore()
	{
		$user = m::mock();
		$group = m::mock();

		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->groups->shouldReceive('findById')->with(1)->once()->andReturn($group);
		$this->users->shouldReceive('addGroup')->with($user, $group)->once();
		$this->route('post', 'users.groups.store', 1, array('group' => 1));
		$this->assertRedirectedToRoute('users.groups.index', 1);
	}

	public function testDestroy()
	{
		$user = m::mock();
		$group = m::mock();
		$this->users->shouldReceive('findById')->with(1)->once()->andReturn($user);
		$this->groups->shouldReceive('findById')->with(1)->once()->andReturn($group);
		$this->users->shouldReceive('removeGroup')->with($user, $group);
		$this->route('delete', 'users.groups.destroy', array(1, 1));
		$this->assertRedirectedToRoute('users.groups.index', 1);
	}

}