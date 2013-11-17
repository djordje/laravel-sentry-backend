@extends('laravel-backend-layout::default')

@section('title') Add to groups @stop

@section('left_col')
	@include('laravel-sentry-backend::usergroups._nav')
@stop

@section('content')
<table class="table table-hover table-striped table-bordered">
	<tbody>
	@foreach ($groups as $group)
	<tr>
		<td>
			{{ $group->name }}

			<div class="pull-right">
				{{ Form::open(array('route' => array('users.groups.store', $user->id), 'style' => 'display: inline-block;')) }}
				<input type="hidden" name="group" value="{{$group->id}}"/>
				<button type="submit" class="btn btn-xs btn-default" data-confirm="Are you sure?">
					<i class="glyphicon glyphicon-plus"></i> Add
				</button>
				{{ Form::close() }}
			</div>
		</td>
	</tr>
	@endforeach
	</tbody>
</table>
@stop