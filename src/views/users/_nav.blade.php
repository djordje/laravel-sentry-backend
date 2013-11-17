<div class="list-group">
	<div class="list-group-item">
		<h4 class="list-group-item-heading">
			<i class="glyphicon glyphicon-user"></i>
			Manage users
		</h4>
	</div>
</div>

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-list"></i> Users list', 'href' => route('users.index')),
	array('<i class="glyphicon glyphicon-plus"></i> Create user', 'href' => route('users.create'))
))) }}

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-th"></i> Manage groups', 'href' => route('groups.index'))
))) }}