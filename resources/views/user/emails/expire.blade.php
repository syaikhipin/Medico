<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Hello {{ $user['first_name'] }} {{ $user['last_name'] }}, </h2>
		<p> Your Subscription will be ended  at   {{$user['expire'] }} </p>

		<p>Kindly renew your subscription to get continuous support.</p>
		{{ CNF_APPNAME }} 
	</body>
</html>