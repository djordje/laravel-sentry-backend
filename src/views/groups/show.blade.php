@extends('laravel-backend-layout::default')

@section('title') {{ $group->name }} group overview @stop

@section('left_col')
	@include('laravel-sentry-backend::groups._nav')
@stop

@section('content')
<h4><strong>{{ $group->name }}</strong> group permissions:</h4>

<ul>
@foreach ($group->permissions as $name => $value)
	<li>{{ $name }}</li>
@endforeach
</ul>
@stop