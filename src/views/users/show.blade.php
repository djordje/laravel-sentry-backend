@extends('laravel-backend-layout::default')

@section('title') Users '{{ $user->email }}' overview: @stop

@section ('left_col')
	@include ('laravel-sentry-backend::users._nav')
@stop

@section('content')

<div class="row">
	<div class="col-sm-6">
		<h4><small>Account:</small> {{ $user->email }}</h4>
		<hr/>

		<p><strong>Assigned to groups:</strong></p>
		<ul>
		@foreach ($user->getGroups() as $group)
			<li>{{ $group->name }}</li>
		@endforeach
		</ul>
	</div>
	<div class="col-sm-6">
		<table class="table table-bordered">
			<tbody>
			<tr>
				<th>First name</th>
				<td>{{$user->first_name}}</td>
			</tr>
			<tr>
				<th>Last name</th>
				<td>{{$user->last_name}}</td>
			</tr>
			<tr>
				<th>Created at</th>
				<td>{{ $user->created_at }}</td>
			</tr>
			<tr>
				<th>Activated at</th>
				<td>{{ $user->activated_at }}</td>
			</tr>
			<tr>
				<th>Updated at</th>
				<td>{{ $user->updated_at }}</td>
			</tr>
			<tr>
				<th>Last login</th>
				<td>{{ $user->last_login }}</td>
			</tr>
			</tbody>
		</table>
	</div>
</div>

@stop