<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

Hello {!! \SiteHelpers::gridDisplayView($doctor->entry_by,'id','1:tb_users:id:first_name|last_name') !!}
You had started  sponsorship plan {!! \SiteHelpers::gridDisplayView($doctor->Plan,'id','1:tb_sponsorship_plans:id:name') !!}
will be expired on {{ $doctor->Enddate }}
<hr>
{{ CNF_APPNAME }}
</body>
</html>