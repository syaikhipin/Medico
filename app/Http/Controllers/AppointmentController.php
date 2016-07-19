<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Appointment;
use App\Models\Calendar;
use App\Models\DoctorPatient;
use App\Models\Patient;
use App\User;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator, Input, Redirect ;


class AppointmentController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'appointment';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Appointment();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'appointment',
			'pageUrl'			=>  url('appointment'),
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'StartAt');
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'Desc');
		// End Filter sort and order for query 
		// Filter Search for query

		$filter = (!is_null($request->search) ? $this->buildSearch() : '');
		$timezone = \SiteHelpers::gridDisplayView(Auth::user()->id,'DoctorID','1:tb_doctor:UserID:timezone');
		if($timezone=='')
		{
			$timezone = 'UTC';
		}
		$now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),$timezone,'Y-m-d H:i:s');
		$filter .= " AND tb_appointment.StartAt <= '".$now."' AND isCancelled= 0";

		
		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);

		// Get Query 
		$results = $this->model->getRows( $params );		
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('appointment');
		
		$this->data['rowData']		= $results['rows'];
		// Build Pagination 
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();	
		// Row grid Number 
		$this->data['i']			= ($page * $params['limit'])- $params['limit']; 
		// Grid Configuration 
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);		
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		return view('appointment.index',$this->data);
	}	



	function getUpdate(Request $request, $id = null)
	{
	
		if($id =='')
		{
			if($this->access['is_add'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}	
		
		if($id !='')
		{
			if($this->access['is_edit'] ==0 )
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
		}				
				
		$row = $this->model->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_appointment'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		
		$this->data['id'] = $id;
		return view('appointment.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_appointment'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('appointment.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_appointment');
			
			$id = $this->model->insertRow($data , $request->input('AppointmentID'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'appointment/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'appointment?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('AppointmentID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('appointment/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('appointment')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('appointment')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}


	public function postBook(Request $request){

		Cookie::queue(cookie('DoctorID',$request->input('DoctorID'), 30));
		Cookie::queue('ClinicID',$request->input('ClinicID'),30);
		Cookie::queue('ap_StartAt',$request->input('appointment_start_time'),30);
		Cookie::queue('ap_EndAt',date('Y-m-d H:i:s', strtotime('+'.$request->input('VisitTime').' minutes', strtotime($request->input('appointment_start_time')))),30);
		if(!\Auth::user()){
			return Redirect::to('user/login?continue=appointment/book');
		}
		else
		{
			$this->data['pageTitle'] = 'Book Appointment';
			$this->data['pageNote'] ='';
			$this->data['pageMetakey'] =  CNF_METAKEY ;
			$this->data['pageMetadesc'] = CNF_METADESC ;
			$this->data['breadcrumb'] = 'active';
			$page = 'layouts.'.CNF_THEME.'.index';
			$this->data['pages']= 'pages.book';
			$this->data['DoctorID']= $request->input('DoctorID');
			$this->data['ClinicID']= $request->input('ClinicID');
			$this->data['ap_StartAt']= $request->input('appointment_start_time');
			$this->data['ap_EndAt']= date('Y-m-d H:i:s', strtotime('+'.$request->input('VisitTime').' minutes', strtotime($request->input('appointment_start_time'))));
			$this->data['patients'] = Patient::where('entry_by',Auth::user()->id)->get();
			return view($page,$this->data);
		}

	}

	public function getBook()
	{

		if(Cookie::get('DoctorID')!=null) {
			$this->data['pageTitle'] = 'Book Appointment';
			$this->data['pageNote'] ='';
			$this->data['pageMetakey'] =  CNF_METAKEY ;
			$this->data['pageMetadesc'] = CNF_METADESC ;
			$this->data['breadcrumb'] = 'active';
			$page = 'layouts.'.CNF_THEME.'.index';
			$this->data['pages']= 'pages.book';
			$this->data['DoctorID']= Cookie::get('DoctorID');
			$this->data['ClinicID']= Cookie::get('ClinicID');
			$this->data['ap_StartAt']= Cookie::get('ap_StartAt');
			$this->data['ap_EndAt']= Cookie::get('ap_EndAt');
			$this->data['patients'] = Patient::where('entry_by',Auth::user()->id)->get();
			return view($page,$this->data);
		}
		else{
			return Redirect::to('');
		}

	}

	public function postConfirm(Request $request)
	{
		$data= $request->all();
		$data['entry_by']= \SiteHelpers::gridDisplayView($request->input('DoctorID'),'DoctorID','1:tb_doctor:DoctorID:UserID');
		$data['CreatedAt']= date('Y-m-d',strtotime($request->input('StartAt')));
		$id = $this->model->insertRow($data, $request->input('AppointmentID'));
		$doctorpatient = array(
			'DoctorUserID' => \SiteHelpers::gridDisplayView($request->DoctorID,'DoctorID','1:tb_doctor:DoctorID:UserID'),
			'FamilyMemberID' => \Auth::user()->id
		);


		$dp = DoctorPatient::firstOrCreate($doctorpatient);
		$dp->update($doctorpatient);

		$event= new Calendar();
		$event->title= 'Appointment for'.\SiteHelpers::gridDisplayView($request->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name');
		$event->description= "Doctor: ".\SiteHelpers::gridDisplayView($request->DoctorID,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID").'<br/>  Clinic : '.
			\SiteHelpers::gridDisplayView($request->ClinicID,'ClinicID','1:tb_clinic:ClinicID:Name');
		$event->start= $request->StartAt;
		$event->end= $request->EndAt;
		$event->entry_by = \Auth::user()->id;
		$event->save();
		$patient = Patient::find($request->PatientID);

		$emails[] = \Auth::user()->email;
		if($patient->Email!=null){
			$emails[] = $patient->Email;
		}

		Mail::send('emails.confirm', ['patient' => $patient, 'appointment' => $request->StartAt], function ($m) use ($emails)  {
			$m->to($emails)->subject("[ " .CNF_APPNAME." ] Appointment Confirmed ");
		});

		$notif = array(
		'url'   =>  url('notification'),
		'userid'    => \Auth::user()->id,
		'title'     => 'Appointment Confirmed ',
		'note'      => 'Your Appointment for '. $patient->first_name .' '.$patient->last_name .' scheduled on '. date('Y-m-d h:i a',strtotime($request->StartAt)).' is confirmed.',
		'entry_by'	=> \Auth::user()->id,
	);
		\SximoHelpers::storeNote($notif);

		$notif = array(
			'url'   =>  url('notification'),
			'userid'    => \SiteHelpers::gridDisplayView($request->input('DoctorID'),'DoctorID','1:tb_doctor:DoctorID:UserID'),
			'title'     => 'New Appointment Scheduled ',
			'note'      => 'New  Appointment for '. $patient->first_name .' '.$patient->last_name .'is  scheduled on '. date('Y-m-d h:i a',strtotime($request->StartAt)).' by '.\Auth::user()->first_name.' '.\Auth::user()->last_name.'.',
			'entry_by'	=>\SiteHelpers::gridDisplayView($request->input('DoctorID'),'DoctorID','1:tb_doctor:DoctorID:UserID'),
		);
		\SximoHelpers::storeNote($notif);

		Cookie::queue('DoctorID', null, -1);
		Cookie::queue('ClinicID',null,-1);
		Cookie::queue('ap_StartAt',null,-1);
		Cookie::queue('ap_EndAt',null,-1);
		return Redirect::to('')->with('message', \SiteHelpers::alert('success','Thank You , Your Appointment is confirmed !'));

	}

	public function getCancel(Request $request,$id){
		$ap = Appointment::find($id);
		$ap->isCancelled = 1;
		$ap->save();
		return Redirect::to('dashboard')->with('messagetext',\Lang::get('Appointment cancelled successfully'))->with('msgstatus','success');

	}

	public function getPending(Request $request)
	{
		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'StartAt');
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'desc');
		// End Filter sort and order for query
		// Filter Search for query

		$filter = (!is_null($request->search) ? $this->buildSearch() : '');
		$timezone = \SiteHelpers::gridDisplayView(Auth::user()->id,'DoctorID','1:tb_doctor:UserID:timezone');
		if($timezone=='')
		{
			$timezone = 'UTC';
		}
		$now = \Camroncade\Timezone\Facades\Timezone::convertFromUTC(date('Y-m-d H:i:s'),$timezone,'Y-m-d H:i:s');
		$filter .= " AND tb_appointment.StartAt >= '".$now."'";


		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);

		// Get Query
		$results = $this->model->getRows( $params );

		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
		$pagination->setPath('appointment');

		$this->data['rowData']		= $results['rows'];
		// Build Pagination
		$this->data['pagination']	= $pagination;
		// Build pager number and append current param GET
		$this->data['pager'] 		= $this->injectPaginate();
		// Row grid Number
		$this->data['i']			= ($page * $params['limit'])- $params['limit'];
		// Grid Configuration
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);
		// Group users permission
		$this->data['access']		= $this->access;
		$this->data['access']['is_add']="0";
		$this->data['access']['is_edit']="0";
        $this->data['access']['is_detail']="0";
		// Detail from master if any

		// Master detail link if any
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
		// Render into template
		return view('appointment.index',$this->data);
	}

	public function  getReject($id){
		$ap = Appointment::find($id);
		$ap->isCancelled = 1;
		$ap->save();
		$patient = Patient::find($ap->PatientID);

		$familyPerson = User::find($patient->entry_by);
		$emails = [$familyPerson->email];
		if($patient->Email!=null){
			$emails[] = $patient->Email;
		}

//		//$subject = "[ " .CNF_APPNAME." ] Appointment Cancellation ";
//		//$message = view('emails.reject',['patient' => $patient, 'appointment' => $ap]);
//		$headers  = 'MIME-Version: 1.0' . "\r\n";
//		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//		$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
//		mail(implode(',',$emails), $subject, $message, $headers);


		Mail::send('emails.reject', ['patient' => $patient, 'appointment' => $ap], function ($m) use ($emails)  {
			$m->to($emails)->subject( "[ " .CNF_APPNAME." ] Appointment Cancellation ");
		});

		$notif = array(
			'url'   => url('notification'),
			'userid'    => $familyPerson->id,
			'title'     => 'Appointment Cancelled ',
			'note'      => 'Your Appointment for '. $patient->first_name .' '.$patient->last_name .' scheduled on '. date('Y-m-d h:i a',strtotime($ap->StartAt)).' is cancelled due to some reason.',
			'entry_by'	=> $familyPerson->id,
		);
		\SximoHelpers::storeNote($notif);

		return Redirect::to('appointment/pending')->with('messagetext',\Lang::get('Appointment Rejected successfully'))->with('msgstatus','success');
	}


}