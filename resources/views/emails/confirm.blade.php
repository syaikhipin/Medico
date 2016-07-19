<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

<p> Your Appointment for {{ $patient->first_name }} {{ $patient->last_name }} scheduled on {!! date('Y-m-d h:i a',strtotime($appointment)) !!} is confirmed. </p>


{{ CNF_APPNAME }}
</body>
</html>