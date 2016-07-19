<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Hello {{ $user['first_name'] }} {{ $user['last_name'] }}, </h2>
		<p> Your Subscription ended at   {{$user['expire'] }}  </p>

		<p>To start new subscription <a href='{{ URL::to('/user/subscribe') }}'>Click here </a></p>
		{{ CNF_APPNAME }} 
	</body>
</html>