{{ Form::open(array('route' => 'session.destroy', 'method' => 'delete', 'style' => 'display: inline-block;')) }}

	{{ Form::submit('Logout', array('class' => 'btn btn-warning')) }}

{{ Form::close() }}