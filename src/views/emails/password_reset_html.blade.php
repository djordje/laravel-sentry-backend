<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Your password reset link</title>
</head>
<body>

<style>
	body {
		font-family: Arial, Helvetica, sans-serif;
		font-size: 12px;
		line-height: 1.2em;
		background: #fff;
		color: #333;
	}

	a, a:visited, a:hover, a:active {
		text-decoration: none;
		color: #7abaff;
	}
</style>

<p>
	You should visit {{ link_to_route('password-reset.edit', 'this link', array($user->id, $code)) }} to reset your password.
</p>

</body>
</html>