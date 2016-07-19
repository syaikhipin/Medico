@extends('layouts.app')

@section('content')
<script type="text/javascript" src="{{ asset('sximo/js/plugins/fullcalendar/lib/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('sximo/js/plugins/fullcalendar/fullcalendar/fullcalendar.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ asset('sximo/js/plugins/fullcalendar/fullcalendar/fullcalendar.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('sximo/js/plugins/fullcalendar/fullcalendar/fullcalendar.print.css') }}">


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
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	
	    <div class="toolbar-line ">
			@if($access['is_add'] ==1)
	   		<a href="{{ URL::to('calendar/update') }}" onclick="SximoModal(this.href,'Add new date'); return false;" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
			<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
			@endif  
			@if($access['is_remove'] ==1)
			<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
			<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
			@endif 		
			@if($access['is_excel'] ==1)
			<a href="{{ URL::to('calendar/download') }}" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_download') }}">
			<i class="fa fa-download"></i>&nbsp;{{ Lang::get('core.btn_download') }} </a>
			@endif

		</div> 		

	
		<div style="padding:10px; background:#fff;">
			<div id='calendar' > </div>
		</div>	

	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#SximoTable').attr('action','{{ URL::to("calendar/multisearch")}}');
		$('#SximoTable').submit();
	});
	
});	
</script>


<script>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '{{ date("Y-m-d")}}',
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				SximoModal('{{ url("calendar/update?s='+start+'") }}','Add New Date');
				
			},
		   	eventClick: function(calEvent, jsEvent, view) {
		   		//alert(calEvent.id);
		   		var id = calEvent.id;
				SximoModal('{!! url("calendar/update/'+ id +'") !!}','Edit :'+calEvent.title );				
		
			},			
			editable: true,
			events: {
				url: '{{ url("calendar/jsondata") }}',
				error: function() {
					$('#script-warning').show();
				}
			},
			eventDrop: function(event, revertFunc) {
				if (confirm("Are you sure about this change?")) {
					
					$.post( '{{url("calendar/savedrop") }}', 
					{ id:event.id,start : event.start.format(),end : event.end.format()});					
				} else {
					revertFunc();
				}
		
			}		
		});
		
	});

</script>

<style>
	#script-warning {
		display: none;
		background: #eee;
		border-bottom: 1px solid #ddd;
		padding: 0 10px;
		line-height: 40px;
		text-align: center;
		font-weight: bold;
		font-size: 12px;
		color: red;
	}
	.fc-event-inner { background:#0099CC; color:#fff;}

	#loading {
		display: none;
		position: absolute;
		top: 10px;
		right: 10px;
	}

</style>			
@stop