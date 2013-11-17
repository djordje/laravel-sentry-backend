<?php namespace Djordje\LaravelSentryBackend\Services\Sentry;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;

class Groups {

	/**
	 * Last group's error
	 *
	 * @var string
	 */
	protected $error;

	/**
	 * Get last group's error
	 *
	 * @return string
	 */
	public function getError()
	{
		return $this->error;
	}

	/**
	 * Create new group
	 *
	 * @param array $data
	 * @return bool
	 */
	public function create(array $data)
	{
		try
		{
			Sentry::createGroup($data);
		}
		catch (NameRequiredException $e)
		{
			$this->error = 'Name field is required';
			return false;
		}
		catch (GroupExistsException $e)
		{
			$this->error = 'Group already exists';
			return false;
		}

		return true;
	}

	/**
	 * Return array of groups
	 *
	 * @return array
	 */
	public function findAll()
	{
		return Sentry::findAllGroups();
	}

	/**
	 * Find the group by ID.
	 *
	 * @param  int  $id
	 * @return \Cartalyst\Sentry\Groups\GroupInterface|false
	 */
	public function findById($id)
	{
		try
		{
			$group = Sentry::findGroupById($id);
		}
		catch (GroupNotFoundException $e)
		{
			$this->error = 'Group was not found.';
			return false;
		}

		return $group;
	}

	/**
	 * Find the group by name.
	 *
	 * @param string $name
	 * @return \Cartalyst\Sentry\Groups\GroupInterface|false
	 */
	public function findByName($name)
	{
		try
		{
			$group = Sentry::findGroupByName($name);
		}
		catch (GroupNotFoundException $e)
		{
			$this->error = 'Group was not found.';
			return false;
		}

		return $group;
	}

	/**
	 * Updated found group with provided data
	 *
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public function update($id, array $data)
	{
		$saved = false;
		try
		{
			$group = Sentry::findGroupById($id);

			if ( ! empty($data['name']))
			{
				$group->name = $data['name'];
			}

			if ( ! empty($data['permissions']))
			{
				$group->permissions = $data['permissions'];
			}

			if ($group->save())
			{
				$saved = true;
			}
		}
		catch (GroupExistsException $e)
		{
			$this->error = 'Group already exists.';
			return false;
		}
		catch (GroupNotFoundException $e)
		{
			$this->error = 'Group was not found.';
			return false;
		}

		return $saved;
	}

	/**
	 * Delete found group
	 *
	 * @param int $id
	 * @return bool
	 */
	public function delete($id)
	{
		try
		{
			$group = Sentry::findGroupById($id);

			$group->delete();
		}
		catch (GroupNotFoundException $e)
		{
			return false;
		}

		return true;
	}

//	public function getSelectList()
//	{
//		$groupProvider = Sentry::getGroupProvider();
//
//		return $groupProvider->createModel()->lists('name', 'id');
//	}

}