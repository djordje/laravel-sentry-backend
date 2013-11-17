@extends('laravel-backend-layout::default')

@section('title') Request password reset @stop

@section('content')

@if (Session::has('password_reset_sent'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p><strong>Password reset sent!</strong> Please visit your mail box for reset link.</p>
</div>
@endif

<div class="row">
	{{ Form::open(array('route' => 'password-reset.store', 'class' => 'col-sm-6', 'role' => 'form')) }}

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
			<p>Please fill your email address (one that you used for registration and log in, and we will send you password reset link.</p>
		</div>

		<div class="form-group">
			{{ Form::submit('Request', array('class' => 'btn btn-primary')) }}
			{{ link_to_route('session.create', 'Cancel', null, array('class' => 'btn btn-default pull-right')) }}
		</div>

	{{ Form::close() }}
</div>

@stop