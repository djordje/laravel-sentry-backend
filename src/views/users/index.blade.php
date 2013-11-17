@extends('laravel-backend-layout::default')

@section('title') List of users @stop

@section('left_col')
	@include('laravel-sentry-backend::users._nav')
@stop

@section('content')
<table class="table table-striped table-hover table-bordered">
	<tbody>
	@foreach ($users as $user)
	@if ( ! $user->activated)
	<tr class="warning">
	@else
	<tr>
	@endif
		<td>
			{{ link_to_route('users.show', $user->email, $user->id) }}

			<div class="pull-right">
				@if ( ! $user->activated)
				{{ Form::open(array('route' => array('users.activate', $user->id), 'method' => 'patch', 'style' => 'display: inline-block;')) }}
				<button type="submit" class="btn btn-xs btn-primary" data-confirm="Are you sure?">
					Activate
				</button>
				{{ Form::close() }}
				@endif

				<div class="btn-group">
					{{ HTML::decode(link_to_route('users.edit', '<i class="glyphicon glyphicon-edit"></i> Edit', $user->id, array('class' => 'btn btn-xs btn-default'))) }}
					<button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li>{{ link_to_route('users.groups.index', 'Manage groups', $user->id) }}</li>
					</ul>
				</div>

				{{ Form::open(array('route' => array('users.destroy', $user->id), 'method' => 'delete', 'style' => 'display: inline-block;')) }}
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