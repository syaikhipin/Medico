@extends('layouts.app')

@section('content')
	{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
	<div class="page-content row">
		<!-- Page header -->
		<div class="page-header">
			<div class="page-title">
				<h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>
			</div>

			<ul class="breadcrumb">
				<li><a href="{{ URL::to('dashboard') }}"> Dashboard </a></li>
				<li class="active">{{ $pageTitle }}</li>
			</ul>

		</div>


		<div class="page-content-wrapper m-t">

			<div class="sbox animated fadeInRight">
				<div class="sbox-title"> <h5> <i class="fa fa-table"></i> </h5>
					<div class="sbox-tools" >
						<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips" title="Clear Search" ><i class="fa fa-trash-o"></i> Clear Search </a>
						@if(Session::get('gid') ==1)
							<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
						@endif
					</div>
				</div>
				<div class="sbox-content">
					<div class="toolbar-line ">
						@if($access['is_add'] ==1)
							<a href="{{ URL::to('appointment/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
								<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
						@endif
						@if($access['is_remove'] ==1)
							<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
								<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
						@endif
						<a href="{{ URL::to( 'appointment/search/native') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" ><i class="fa fa-search"></i> Search</a>
						@if($access['is_excel'] ==1)
							<a href="{{ URL::to('appointment/download?return='.$return) }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
								<i class="fa fa-download"></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
						@endif

					</div>



					{!! Form::open(array('url'=>'appointment/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
					<div class="table-responsive" style="min-height:300px;">
						<table class="table table-striped ">
							<thead>
							<tr>
								<th class="number"> No </th>
								<th> <input type="checkbox" class="checkall" /></th>
								<th>Doctor</th>

								@foreach ($tableGrid as $t)

									<?php if($t['view'] =='1') :
										$limited = isset($t['limited']) ? $t['limited'] :'';
										if(SiteHelpers::filterColumn($limited ))
										{
											if($access['is_edit'] ==0 && $t['field']=='Diagnosis'){

											}
											else{
												echo '<th align="'.$t['align'].'" width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';
											}
										}
									endif;?>
								@endforeach
								@if($access['is_edit'] ==1)
									<th width="70" >{{ Lang::get('core.btn_action') }}</th>
								@endif
							</tr>
							</thead>

							<tbody>
							@foreach ($rowData as $row)
								<tr>
									<td width="30"> {{ ++$i }} </td>
									<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->AppointmentID }}" />  </td>
									<td width="100">
										{!! SiteHelpers::gridDisplayView($row->DoctorID,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID") !!}
									</td>
									@foreach ($tableGrid as $field)
										@if($field['view'] =='1')
											<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>

											@if(SiteHelpers::filterColumn($limited ))

												<?php
												$conn = (isset($field['conn']) ? $field['conn'] : array() );
												$value = AjaxHelpers::gridFormater($row->$field['field'], $row , $field['attribute'],$conn);
												?>
												@if($access['is_edit'] ==0 && $field['field']=='Diagnosis')

												@else
													<td align="<?php echo $field['align'];?>" data-values="{{ $row->$field['field'] }}" data-field="{{ $field['field'] }}" data-format="{{ htmlentities($value) }}">
														@if($field['field']=='isCancelled')
															@if($row->$field['field']==1)
																<label class="label label-default">Cancelled</label>
															@else

																<?php
																 $now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),\SiteHelpers::gridDisplayView($row->DoctorID,'DoctorID','1:tb_doctor:DoctorID:timezone'),'Y-m-d H:i:s');
																?>

																@if($now > $row->StartAt)
																	<label class="label label-success">Completed</label>
																@else
																	<a href="{{ url('appointment/reject/'.$row->AppointmentID) }}" id="cancel_ap" class="label label-danger">Reject</a>
																@endif
															@endif
														@else
															{!! $value !!}
														@endif
													</td>
												@endif
											@endif
										@endif
									@endforeach
									@if($access['is_detail'] ==1)
										<td>
											@if($access['is_detail'] ==1)
												<a href="{{ URL::to('appointment/show/'.$row->AppointmentID.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
											@endif
											@if($access['is_edit'] ==1)
												<a  href="{{ URL::to('appointment/update/'.$row->AppointmentID.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
											@endif


										</td>
									@endif
								</tr>

							@endforeach

							</tbody>

						</table>
						<input type="hidden" name="md" value="" />
					</div>
					{!! Form::close() !!}
					@include('footer')
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function(){

			$('.do-quick-search').click(function(){
				$('#SximoTable').attr('action','{{ URL::to("appointment/multisearch")}}');
				$('#SximoTable').submit();
			});

		});
	</script>
@stop