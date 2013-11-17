<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Your account activation code</title>
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

	h3 {
		font-size: 16px;
		font-weight: normal;
	}

	a, a:visited, a:hover, a:active {
		text-decoration: none;
		color: #7abaff;
	}
</style>

<h3>Thank you for registration!</h3>

<p>
	You should activate your new account by visiting {{ link_to_route('profile.activate', 'this link', array($user->id, $code)) }} before you can use it.
</p>

</body>
</html>