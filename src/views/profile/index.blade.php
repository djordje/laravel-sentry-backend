@extends('laravel-backend-layout::default')

@section('title') Profile @stop

@section('content')

@if (Session::has('success_password_change'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>You have changed your password successfully!</p>
</div>
@endif

<h4>
	Welcome aboard!

	<div class="pull-right">
		{{ link_to_route('profile.edit', 'Edit profile', $user->id, array('class' => 'btn btn-default')) }}
		{{ link_to_route('profile.password.create', 'Change password', $user->id, array('class' => 'btn btn-default')) }}
		@include('laravel-sentry-backend::session._destroy')
	</div>
</h4>

<table class="table table-bordered table-striped table-hover">
	<tbody>
	<tr>
		<th>Email</th>
		<td>{{ $user->email }}</td>
	</tr>
	<tr>
		<th>First name</th>
		<td>{{ $user->first_name }}</td>
	</tr>
	<tr>
		<th>Last name</th>
		<td>{{ $user->last_name }}</td>
	</tr>
	<tr>
		<th>You are in this user groups:</th>
		<td>
			@if (($groups = $user->getGroups()) && $groups->count())
				@foreach ($groups as $group)
				{{ $group->name }}<br>
				@endforeach
			@else
				[none]
			@endif
		</td>
	</tr>
	</tbody>
</table>
@stop