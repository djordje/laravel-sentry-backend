@extends('laravel-backend-layout::default')

@section('title') Edit profile @stop

@section('content')

@if ($errors->get('error'))
<div class="alert alert-danger alert-dismissable">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	<p>{{ $errors->first('error') }}</p>
</div>
@endif

<div class="row">
	{{ Form::model($user, array('route' => array('profile.update', $user->id), 'method' => 'put', 'class' => 'col-sm-6', 'role' => 'form')) }}

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
		{{ link_to_route('profile.index', 'Cancel', null, array('class' => 'btn btn-default pull-right')) }}
	</div>

	{{ Form::close() }}
</div>

@stop