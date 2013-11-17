@extends('laravel-backend-layout::default')

@section('title') Edit user '{{ $user->email }}' @stop

@section('left_col')
	@include('laravel-sentry-backend::users._nav')
@stop

@section('content')

@if ($errors->get('error'))
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>{{ $errors->first('error') }}</p>
</div>
@endif

<div class="row">
	{{ Form::model($user, array('route' => array('users.update', $user->id), 'method' => 'put', 'class' => 'col-sm-6', 'role' => 'form')) }}

	@if ($errors->get('email'))
		<div class="form-group has-error">
	@else
		<div class="form-group">
	@endif
		{{ Form::label('email', 'Email') }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
		<span class="help-block">{{ $errors->first('email') }}</span>
	</div>

	<div class="form-group">
		{{ Form::label('first_name', 'First name') }}
		{{ Form::text('first_name',null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::label('last_name', 'Last name') }}
		{{ Form::text('last_name',null, array('class' => 'form-control')) }}
	</div>

	<div class="form-group">
		{{ Form::submit('Save', array('class' => 'btn btn-primary')) }}
		{{ Form::reset('Reset', array('class' => 'btn btn-default')) }}
	</div>

	{{ Form::close() }}
</div>

@stop