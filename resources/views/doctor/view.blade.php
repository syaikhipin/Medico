@extends('layouts.app')

@section('content')
	<div class="page-content row">
		<!-- Page header -->
		<div class="page-header">
			<div class="page-title">
				<h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
			</div>
			<ul class="breadcrumb">
				<li><a href="{{ URL::to('dashboard') }}">{{ Lang::get('core.home') }}</a></li>
				<li><a href="{{ URL::to('doctor?return='.$return) }}">{{ $pageTitle }}</a></li>
				<li class="active"> {{ Lang::get('core.detail') }} </li>
			</ul>
		</div>


		<div class="page-content-wrapper">
			<div class="toolbar-line">
				@if($access['is_add'] ==1)
					<a href="{{ URL::to('doctor?return='.$return) }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
					<a href="{{ URL::to('doctor/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
				@else
					<a href="{{ URL::to('doctor/visited') }}" class="tips btn btn-xs btn-default" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
				@endif
			</div>
			<div class="sbox animated fadeInRight">
				<div class="sbox-title">
					<h4> <i class="fa fa-table"></i>
						@if($row->isSponsored==1)
							<label style="float: right" class="label label-success">Sponsored</label>
						@endif
					</h4>
				</div>
				<div class="sbox-content">



					<table class="table table-striped table-bordered" >
						<tbody>
						<tr>
							<td colspan="2" style="text-align: center">  {!! SiteHelpers::showUploadedFile(App\Models\doctor::getDetail($row -> UserID)->avatar,'/uploads/users/') !!}</td>
						</tr>


						<tr>
							<td width='30%' class='label-view text-right'>
								Name
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->UserID,'UserID','1:tb_users:id:first_name|last_name') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								Email
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->UserID,'UserID','1:tb_users:id:email') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								Contact Number
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->UserID,'UserID','1:tb_users:id:ContactNo') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								Gender
							</td>
							<td>{!! SiteHelpers::gridDisplayView($row->UserID,'UserID','1:tb_users:id:Gender') !!} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Expertization', (isset($fields['Expertization']['language'])? $fields['Expertization']['language'] : array())) }}
							</td>
							<td>{{ $row->Expertization }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Degree', (isset($fields['Degree']['language'])? $fields['Degree']['language'] : array())) }}
							</td>
							<td>{{ $row->Degree }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Experience', (isset($fields['Experience']['language'])? $fields['Experience']['language'] : array())) }}
							</td>
							<td>{{ $row->Experience }} </td>

						</tr>

						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Fee', (isset($fields['Fee']['language'])? $fields['Fee']['language'] : array())) }}
							</td>
							<td>{{ $row->Fee }} </td>

						</tr>
						<tr>
							<td width='30%' class='label-view text-right'>
								{{ SiteHelpers::activeLang('Recommendation', (isset($fields['Recommendation']['language'])? $fields['Recommendation']['language'] : array())) }}
							</td>
							<td>{{ $row->Recommendation }} </td>

						</tr>

						</tbody>
					</table>
					<?php $clinic=0 ;
					?>
					<br>
					<h3>Schedule</h3>
					@if($schedule->isEmpty())
						No Schedule available
					@endif
					@foreach($schedule as $sch)
						<h4 style="text-align:center"> {!! SiteHelpers::gridDisplayView($sch->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name|Address') !!} </h4>


						<table class="table table-striped table-bordered">
							<tr>
								<th>Day/Schedule</th>
								<th>Morning From</th>
								<th>Morning To</th>
								<th>Afternoon From</th>
								<th>Afternoon To</th>
							</tr>
							@foreach($sch->detail as $sch_detail)
								<tr>
									<th>{!! SiteHelpers::gridDisplayView($sch_detail->Day,'Day','1:tb_Days:id:Name') !!}</th>
									<td>{{ $sch_detail->FirstSessionStart=='00:00:00'? '-' : $sch_detail->FirstSessionStart }}</td>
									<td>{!! $sch_detail->FirstSessionEnd=='00:00:00'? '-' : $sch_detail->FirstSessionEnd !!}</td>
									<td>{!! $sch_detail->SecondSessionStart=='00:00:00'? '-' : $sch_detail->SecondSessionStart !!}</td>
									<td>{!! $sch_detail->SecondSessionEnd=='00:00:00'? '-' : $sch_detail->SecondSessionEnd !!}</td>
								</tr>
							@endforeach
						</table>
					@endforeach

				</div>
			</div>

		</div>
	</div>

@stop