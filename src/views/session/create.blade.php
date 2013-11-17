@extends('laravel-backend-layout::default')

@section('title') Login @stop

@section('content')

@if (Session::has('success_registration'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p><strong>Successful registration!</strong> Please visit your mail box for activation link.</p>
</div>
@endif

@if (Session::has('success_password_reset'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>Login with your new password.</p>
</div>
@endif

@if (Session::has('login_error'))
<div class="alert alert-warning alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>{{ Session::get('login_error') }}</p>
</div>
@endif

@if (Session::has('error_activation'))
<div class="alert alert-warning alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>{{ Session::get('error_activation') }}</p>
</div>
@endif

@if (Session::has('success_activation'))
<div class="alert alert-success alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>You have successfully activated your account.</p>
</div>
@endif

<div class="row" style="margin-top: 50px;">
{{ Form::open(array('route' => 'session.store', 'class' => 'form-horizontal col-sm-6', 'role' => 'form')) }}

	<div class="form-group">
		{{ Form::label('email', 'Email', array('class' => 'col-sm-3 control-label')) }}
		<div class="col-sm-9">
			{{ Form::text('email', null, array('class' => 'form-control')) }}
		</div>
	</div>

	<div class="form-group">
		{{ Form::label('password', 'Password', array('class' => 'col-sm-3 control-label')) }}
		<div class="col-sm-9">
			{{ Form::password('password', array('class' => 'form-control')) }}
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<div class="checkbox">
				<label>
					{{ Form::checkbox('remember') }} Remember me
				</label>
			</div>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-9">
			<p>You can {{ link_to_route('profile.create', 'register account') }} if you are not member already.</p>
			<p>{{ link_to_route('password-reset.create', 'You cant remember your password?') }}</p>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-9 col-sm-offset-3">
			{{ Form::submit('Login', array('class' => 'btn btn-primary')) }}
		</div>
	</div>

{{ Form::close() }}
</div>

@stop