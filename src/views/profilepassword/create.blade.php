@extends('laravel-backend-layout::default')

@section('title') Change password @stop

@section('content')

<div class="row">
	{{ Form::open(array('route' => array('profile.password.store', $user->id), 'class' => 'form-horizontal col-sm-8', 'role' => 'form')) }}

		@if ($errors->get('old_password'))
			<div class="form-group has-error">
		@else
			<div class="form-group">
		@endif
			{{ Form::label('old_password', 'Old password') }}
			{{ Form::password('old_password', array('class' => 'form-control')) }}
			<span class="help-block">{{ $errors->first('old_password') }}</span>
		</div>

		@if ($errors->get('password'))
			<div class="form-group has-error">
		@else
			<div class="form-group">
		@endif
			{{ Form::label('password', 'Password') }}
			{{ Form::password('password', array('class' => 'form-control')) }}
			<span class="help-block">{{ $errors->first('password') }}</span>
		</div>

		@if ($errors->get('password'))
			<div class="form-group has-error">
		@else
			<div class="form-group">
		@endif
			{{ Form::label('password_confirmation', 'Password confirmation') }}
			{{ Form::password('password_confirmation', array('class' => 'form-control')) }}
		</div>

		<div class="form-group">
			{{ Form::submit('Change', array('class' => 'btn btn-primary')) }}
			{{ link_to_route('profile.index', 'Cancel', null, array('class' => 'btn btn-default pull-right')) }}
		</div>

	{{ Form::close() }}
</div>

@stop