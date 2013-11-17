@extends('laravel-backend-layout::default')

@section('title') Reset password @stop

@section('content')

<div class="row">
	{{ Form::open(array('route' => 'password-reset.update', 'method' => 'put', 'class' => 'col-sm-6', 'role' => 'form')) }}

		{{ Form::hidden('id', $user->id) }}
		{{ Form::hidden('code', $code) }}

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
			{{ Form::submit('Reset', array('class' => 'btn btn-primary')) }}
		</div>

	{{ Form::close() }}
</div>

@stop