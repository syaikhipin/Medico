<style>
	ul.uploadedLists	{
		list-style-type: none;
	}

	#suggestion > li {
		border-bottom: 1px solid rgba(0, 0, 0, 0.2);
		padding: 10px;
	}

	.ui-autocomplete span.hl_results {
		background-color: #e7e7e7;
	}

	/* loading - the AJAX indicator */
	.ui-autocomplete-loading {
		background: white ;
	}

	#suggestion {
		background-color: rgba(0,0,0,0.5);
		list-style: outside  none;
		margin: 0;
		padding: 0;
		position: absolute;
		z-index: 10000;
		width:94%;
		color:white;

	}

	#suggestion > li{
		border-bottom: 1px solid rgba(0, 0, 0, 0.2);
		padding: 10px;
	}

	.ui-autocomplete {
		background-color: rgba(0, 0, 0, 0.5) !important;
		box-sizing: border-box !important;
		color: #fff !important;
		padding: 5px !important;
		width: 400px !important;
		z-index: 1000 !important;
		list-style-type: none !important;
	}

	@media(max-width: 768px){
		.ui-autocomplete{
			width: 95% !important;
		}

	}

	.ui-autocomplete li {
		border-bottom: 1px solid !important;
		padding-bottom: 10px !important;
	}
	.keyword{
		color:#ccc;
		font-size: 12px;
	}


	.ui-autocomplete li {
		font-size: 14px;
		width: 100%;
	}

	.ui-autocomplete li:hover{
		border: solid 2px #c3c3c3;

	}

	span.ui-helper-hidden-accessible{
		display: none;
	}

	/* IE 6 doesn't support max-height
    * we use height instead, but this forces the menu to always be this tall
    */
	* html .ui-autocomplete {
		height: 400px;
	}

</style>
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
				<li><a href="{{ URL::to('clinic?return='.$return) }}">{{ $pageTitle }}</a></li>
				<li class="active">{{ Lang::get('core.addedit') }} </li>
			</ul>

		</div>

		<div class="page-content-wrapper">

			<ul class="parsley-error-list">
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
			<div class="sbox animated fadeInRight">
				<div class="sbox-title"> <h4> <i class="fa fa-table"></i> </h4></div>
				<div class="sbox-content">

					{!! Form::open(array('url'=>'clinic/save?return='.$return, 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}
					<div class="col-md-12">
						<fieldset><legend> clinic</legend>

							<div class="form-group hidethis " style="display:none;">
								<label for="ClinicID" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('ClinicID', (isset($fields['ClinicID']['language'])? $fields['ClinicID']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('ClinicID', $row['ClinicID'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Name" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Name', (isset($fields['Name']['language'])? $fields['Name']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Name', $row['Name'],array('class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Speciality" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Speciality', (isset($fields['Speciality']['language'])? $fields['Speciality']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::select('Speciality[]',$specialities,$row['Speciality'], ['id'=> 'Speciality','class'=>'select2','multiple'])!!}
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Photo" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Photo', (isset($fields['Photo']['language'])? $fields['Photo']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									<input  type='file' name='Photo' id='Photo'  style='width:150px !important;'  />

									<div >
										{!! SiteHelpers::showUploadedFile($row['Photo'],'/uploads/clinics/') !!}
									</div>

								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Description" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Description', (isset($fields['Description']['language'])? $fields['Description']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
					  <textarea name='Description' rows='5' id='Description' class='form-control '
							  >{{ $row['Description'] }}</textarea>
								</div>
								<div class="col-md-2">

								</div>
							</div>
							<div class="form-group  " >
								<label for="Address" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Address', (isset($fields['Address']['language'])? $fields['Address']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Address', $row['Address'],array('class'=>'form-control', 'placeholder'=>'', 'id' => 'Address' )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="City" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('City', (isset($fields['City']['language'])? $fields['City']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('City', $row['City'],array( 'id' => 'City','class'=>'form-control', 'placeholder'=>'',   )) !!}
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="Locality" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Locality', (isset($fields['Address']['language'])? $fields['Locality']['language'] : array())) !!}
								</label>
								<div class="col-md-6">
									{!! Form::text('Locality', $row['Locality'],array('class'=>'form-control ui-autocomplete-input', 'placeholder'=>'', 'id' => 'Locality' ,'autocomplete' => 'off')) !!}
{{--									{!! Form::select('Locality',[],$row['Locality'],array('class'=>'form-control ui-autocomplete-input select2', 'placeholder'=>'', 'id' => 'Locality' )) !!}--}}
										{{--<select name="Locality" id="Locality" class="select2"></select>--}}

									<ul id="suggestion" class="col-md-11"></ul>
								</div>
								<div class="col-md-2">

								</div>
							</div>

							<div class="form-group  " >
								<label for="Gallary" class=" control-label col-md-4 text-left">
									{!! SiteHelpers::activeLang('Gallary', (isset($fields['Gallary']['language'])? $fields['Gallary']['language'] : array())) !!}
								</label>
								<div class="col-md-6">

									<a href="javascript:void(0)" class="btn btn-xs btn-primary pull-right" onclick="addMoreFiles('Gallary')"><i class="fa fa-plus"></i></a>
									<div class="GallaryUpl">
										<input  type='file' name='Gallary[]'  />
									</div>
									<ul class="uploadedLists" >
										<?php $cr= 0;
										$row['Gallary'] = explode(",",$row['Gallary']);
										?>
										@foreach($row['Gallary'] as $files)

											@if(file_exists('./uploads/clinics/gallery/'.$files) && $files !='')

												<li id="cr-<?php echo $cr;?>" class="">
													<div style="padding: 4px">{{--<a href="{{ url('uploads/clinics/gallery/'.$files) }}" target="_blank" >{{ $files }}</a>--}}
														{!! \SiteHelpers::showUploadedFile($files,'/uploads/clinics/gallery/',60,'square') !!}
														<span class="pull-right" rel="cr-<?php echo $cr;?>" onclick=" $(this).parent().remove();"><i class="fa fa-trash-o  btn btn-xs btn-danger"></i></span>
														<input type="hidden" name="currGallary[]" value="{{ $files }}"/>
														<?php ++$cr;?>
													</div>
												</li>
											@endif

										@endforeach
									</ul>

								</div>
								<div class="col-md-2">
									<a href="#" data-toggle="tooltip" placement="left" class="tips" title="upload clinic pictures"><i class="icon-question2"></i></a>
								</div>
							</div>


						</fieldset>
					</div>





					{{--@if($subgrid['access']['is_add'] == '1')--}}
						{{--<hr /><div class="clr clear"></div>--}}

						{{--<h5> Staff </h5>--}}

						{{--<div class="table-responsive">--}}
							{{--<table class="table table-striped ">--}}
								{{--<thead>--}}
								{{--<tr>--}}
									{{--@foreach ($subgrid['tableGrid'] as $t)--}}
										{{--@if($t['view'] =='1' && $t['field'] !='ClinicID')--}}
											{{--<th>--}}
												{{--{{ SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())) }}--}}
											{{--</th>--}}
										{{--@endif--}}
									{{--@endforeach--}}
									{{--<th></th>--}}
								{{--</tr>--}}

								{{--</thead>--}}

								{{--<tbody>--}}
								{{--@if(count($subgrid['rowData'])>=1)--}}
									{{--@foreach ($subgrid['rowData'] as $rows)--}}
										{{--<tr class="clone clonedInput">--}}
											{{--@foreach ($subgrid['tableGrid'] as $field)--}}
												{{--@if($field['view'] =='1' && $field['field'] !='ClinicID')--}}
													{{--<td>--}}
														{{--{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] , $rows->$field['field']) !!}--}}
													{{--</td>--}}
												{{--@endif--}}

											{{--@endforeach--}}
											{{--<td>--}}
												{{--<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>--}}
												{{--<input type="hidden" name="counter[]">--}}
											{{--</td>--}}
										{{--</tr>--}}
									{{--@endforeach--}}


								{{--@else--}}

									{{--<tr class="clone clonedInput">--}}
										{{--@foreach ($subgrid['tableGrid'] as $field)--}}

											{{--@if($field['view'] =='1' && $field['field'] !='ClinicID')--}}
												{{--<td>--}}
													{{--{!! SiteHelpers::bulkForm($field['field'] , $subgrid['tableForm'] ) !!}--}}
												{{--</td>--}}
											{{--@endif--}}

										{{--@endforeach--}}
										{{--<td>--}}
											{{--<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>--}}
											{{--<input type="hidden" name="counter[]">--}}
										{{--</td>--}}

									{{--</tr>--}}
								{{--@endif--}}


								{{--</tbody>--}}

							{{--</table>--}}
							{{--<input type="hidden" name="enable-masterdetail" value="true">--}}
						{{--</div><br /><br />--}}

						{{--<a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> New Item</a>--}}
						{{--<hr />--}}
					{{--@endif--}}

					<div style="clear:both"></div>


					<div class="form-group">
						<label class="col-sm-4 text-right">&nbsp;</label>
						<div class="col-sm-8">
							<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
							<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
							<button type="button" onclick="location.href='{{ URL::to('clinic?return='.$return) }}' " class="btn btn-success btn-sm "><i class="fa  fa-arrow-circle-left "></i>  {{ Lang::get('core.sb_cancel') }} </button>
						</div>

					</div>

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function() {
			$('.addC').relCopy({});


			$('.removeCurrentFiles').on('click', function () {
				var removeUrl = $(this).attr('href');
				$.get(removeUrl, function (response) {
				});
				$(this).parent('div').empty();
				return false;
			});

			$('#Speciality').select2({
						width: '98%',
						tags: true,
					}
			);

//

			$("#Locality").on('input', function () {
////
				var location = $("#City").val();
				var val = this.value;
				if ($(this).val() == "") {
					$("#suggestion").html('')
				} else {
					$.ajax({
						url: "/clinic/localityautocomplete?city=" + location + "&term=" + $(this).val(),
						method: "GET",
						success: function (result) {

							$('#suggestion').css('display', 'inline');
							var dataList = document.getElementById('suggestion');
							dataList.innerHTML = "";
							$("#suggestion").html('')
							jQuery.each(result, function (i, data) {
								var option = document.createElement('li');

								option.innerHTML = data.value;
								option.addEventListener('click', function () {
									$('#Locality').val(option.innerHTML);
									$('#suggestion').css('display', 'none');
								})
								dataList.appendChild(option);

							});
						}
					});
				}

			});
		});

		function initMap() {
			var input =
					document.getElementById('Address');
			var autocomplete = new google.maps.places.Autocomplete(input);
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap"
			async defer></script>
@stop