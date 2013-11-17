<div class="list-group">
	<div class="list-group-item">
		<h4 class="list-group-item-heading">
			<i class="glyphicon glyphicon-th"></i>
			Manage groups
		</h4>
	</div>
</div>

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-list"></i> List of groups', 'href' => route('groups.index')),
	array('<i class="glyphicon glyphicon-plus"></i> Create group', 'href' => route('groups.create'))
))) }}

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-user"></i> Manage users', 'href' => route('users.index'))
))) }}