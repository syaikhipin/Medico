<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>

Hello {!! \SiteHelpers::gridDisplayView($clinic->entry_by,'id','1:tb_users:id:first_name|last_name') !!}
You had started  sponsorship plan {!! \SiteHelpers::gridDisplayView($clinic->Plan,'id','1:tb_sponsorship_plans:id:name') !!} for
{!! \SiteHelpers::gridDisplayView($clinic->ClinicID,'id','1:tb_clinic:ClinicID:Name') !!}
 will be expired on {{ $clinic->Enddate }}
<hr>
{{ CNF_APPNAME }}
</body>
</html>