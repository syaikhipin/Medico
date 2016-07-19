<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<p> Sorry ,</p>
<p> Your Appointment for {{ $patient->first_name }} {{ $patient->last_name }} scheduled on {!! date('Y-m-d h:i a',strtotime($appointment->StartAt)) !!} is cancelled due to some reason. </p>


{{ CNF_APPNAME }}
</body>
</html>