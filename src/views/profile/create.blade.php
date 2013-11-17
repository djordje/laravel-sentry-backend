@extends('laravel-backend-layout::default')

@section('title') Create profile @stop

@section('content')

@if ($errors->get('error'))
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>{{ $errors->first('error') }}</p>
</div>
@endif

<div class="row">
	{{ Form::open(array('route' => 'profile.store', 'class' => 'col-sm-6', 'role' => 'form')) }}

	@if ($errors->get('email'))
		<div class="form-group has-error">
	@else
		<div class="form-group">
	@endif
		{{ Form::label('email', 'Email') }}
		{{ Form::text('email', null, array('class' => 'form-control')) }}
		<span class="help-block">{{ $errors->first('email') }}</span>
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
		{{ link_to_route('session.create', 'Cancel', null, array('class' => 'btn btn-default pull-right')) }}
	</div>

	{{ Form::close() }}
</div>
@stop