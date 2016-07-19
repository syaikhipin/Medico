<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Hello <b>{{ $row->first_name }} {{ $row->last_name }} </b>, </h2>
		<p> You have new mail from  </p>
		<p> Message : </p>
		<div>
			<quote>{{ $Message }}</quote>
		</div>
		<p> For detail mail  , please go to your <a href="{{ url('mailbox') }}"> mailbox </a> </p> 
		<p> Thank You </p><br /><br />
		
		{{ CNF_APPNAME }} 
	</body>
</html>