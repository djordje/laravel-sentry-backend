@extends('laravel-backend-layout::default')

@section('title') Edit '{{ $group->name }}' group @stop

@section('left_col')
	@include('laravel-sentry-backend::groups._nav')
@stop

@section('content')

<div class="row">
	{{ Form::model($group, array('route' => array('groups.update', $group->id), 'method' => 'put', 'class' => 'col-sm-6', 'role' => 'form')) }}

	@if ($errors->get('name'))
		<div class="form-group has-error">
	@else
		<div class="form-group">
	@endif
		{{ Form::label('name', 'Name') }}
		{{ Form::text('name', null, array('class' => 'form-control')) }}
		<span class="help-block">{{ $errors->first('name') }}</span>
	</div>

	<div class="form-group">
		<label>Permissions</label>
		<button class="btn btn-sm btn-default add-permission pull-right" type="button">Add permission</button>
	</div>
	<div id="permissions">
		@foreach ($group->permissions as $name => $value)
		<div class="form-group">
			<div class="input-group">
				<input type="text" class="form-control permission-name" value="{{$name}}" disabled/>
				<input type="hidden" name="permissions[{{$name}}]" value="1"/>
				<div class="input-group-btn">
					<button class="btn btn-danger remove-old-permission" type="button"><i class="glyphicon glyphicon-trash"></i></button>
				</div>
			</div>
		</div>
		@endforeach
	</div>

	<div class="form-group">
		{{ Form::submit('Update', array('class' => 'btn btn-primary')) }}
		{{ link_to_route('groups.index', 'Cancel', null, array('class' => 'btn btn-default')) }}
	</div>

	{{ Form::close() }}
</div>

<div id="permission-template" class="hidden">
	<div class="form-group">
		<div class="input-group">
			<input type="text" class="form-control permission-name"/>
			<input type="hidden" name="" value="1"/>
			<div class="input-group-btn">
				<button class="btn btn-danger remove-permission" type="button"><i class="glyphicon glyphicon-trash"></i></button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var template = $('#permission-template').html();
		var container = $('#permissions');

		$('.add-permission').on('click', function(e) {
			e.preventDefault();
			e.stopPropagation();

			container.append(template);
		});

		container.on('change', '.permission-name', function() {
			var $el = $(this);
			var $dst = $el.parent().find('input[type=hidden]');
			var name = ($el.val()) ? 'permissions[' + $el.val() + ']' : null;

			$dst.attr('name', name);
		});

		container.on('click', '.remove-old-permission', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $el = $(this);

			bootbox.confirm('Are you sure?', function(isConfirmed) {
				if (isConfirmed) {
					$el.parent().parent().find('input[type=hidden]').val(0);
					$el.closest('.form-group').hide();
				}
			});
		});

		container.on('click', '.remove-permission', function(e) {
			e.preventDefault();
			e.stopPropagation();

			var $el = $(this);

			bootbox.confirm('Are you sure?', function(isConfirmed) {
				if (isConfirmed) {
					$el.closest('.form-group').remove();
				}
			});
		});
	});
</script>

@stop