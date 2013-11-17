@extends('laravel-backend-layout::default')

@section('title') Belong to groups @stop

@section('left_col')
	@include ('laravel-sentry-backend::usergroups._nav')
@stop

@section('content')
<table class="table table-hover table-striped table-bordered">
	<thead></thead>
	<tbody>
	@foreach ($userGroups as $group)
	<tr>
		<td>
			{{$group->name}}

			<div class="pull-right">
				{{ Form::open(array('route' => array('users.groups.destroy', $user->id, $group->id), 'method' => 'delete', 'style' => 'display: inline-block;')) }}
				<button type="submit" class="btn btn-xs btn-danger" data-confirm="Are you sure?">
					<i class="glyphicon glyphicon-trash"></i> Delete
				</button>
				{{ Form::close() }}
			</div>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
@stop