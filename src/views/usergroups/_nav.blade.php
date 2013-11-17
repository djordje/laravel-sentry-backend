<div class="list-group">
	<div class="list-group-item">
		<h4 class="list-group-item-heading">Manage groups for:</h4>
		{{ link_to_route('users.show', $user->email, $user->id, array('class' => 'list-group-item-text')) }}
	</div>
</div>

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-ok-sign"></i> Belong to groups', 'href' => route('users.groups.index', $user->id)),
	array('<i class="glyphicon glyphicon-plus-sign"></i> Add to groups', 'href' => route('users.groups.create', $user->id))
))) }}

{{  \Djordje\LaravelTwbsHelpers\Facades\TwbsListGroup::build(array('items' => array(
	array('<i class="glyphicon glyphicon-user"></i> Manage users', 'href' => route('users.index')),
	array('<i class="glyphicon glyphicon-th"></i> Manage groups', 'href' => route('groups.index'))
))) }}