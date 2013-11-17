@extends('laravel-backend-layout::default')

@section('title') List of groups @stop

@section('left_col')
	@include('laravel-sentry-backend::groups._nav')
@stop

@section('content')
<table class="table table-hover table-bordered table-striped">
	<tbody>
	@foreach ($groups as $group)
		<tr>
			<td>
				{{ link_to_route('groups.show', $group->name, $group->id) }}

				<div class="pull-right">
					{{ HTML::decode(link_to_route('groups.edit', '<i class="glyphicon glyphicon-edit"></i> Edit', $group->id, array('class' => 'btn btn-xs btn-default'))) }}
					{{ Form::open(array('route' => array('groups.destroy', $group->id), 'method' => 'delete', 'style' => 'display: inline-block;')) }}
						<button type="submit" class="btn btn-xs btn-danger" data-confirm="Are you sure?">
							<i class="glyphicon glyphicon-trash"></i> Delete
						</button>
					{{ Form::close() }}
				</div>
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
@stop