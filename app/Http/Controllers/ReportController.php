<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Mailbox;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Mail;
use Validator, Input, Redirect ;


class ReportController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'report';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Report();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'report',
			'pageUrl'			=>  url('report'),
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'ReportID'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');

		
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
		$pagination->setPath('report');
		
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
		return view('report.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_reports'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		
		$this->data['id'] = $id;
		return view('report.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_reports'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('report.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_report');
			
			$id = $this->model->insertRow($data , $request->input('ReportID'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'report/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'report?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('ReportID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('report/update/')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('report')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('report')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}

	public function getMail($id){
		$this->data['row'] = Report::find($id);
		$patients= Patient::where('entry_by',\Auth::user()->id)->lists('PatientID');

		$doctors= Appointment::whereIn('PatientID',$patients)->distinct()->lists('DoctorID');
		$this->data['doctors']= Doctor::whereIn('DoctorID',$doctors)->lists('UserID');
		return (String)view('report.mail',$this->data);
	}

	public function postMail(Request $request,$id = null){
		$report = Report::find($id);
		$user = User::find($request->input('DoctorID'));
		$data= array();
		$data['Subject'] = $report->Title.' for ' . \SiteHelpers::gridDisplayView($report->PatientID,'PatientID','1:tb_patient:PatientID:first_name|last_name');
		$this->mail_attachment($request,$report,$user,$data['Subject']);
		$data['SenderID'] = \Auth::user()->id;
		$data['ReceiverID'] = $request->input('DoctorID');
		$data['Message'] =  $request->input('message');
		$data['Message'] .= '</br></br></br><a href="'.url('uploads/Reports').'/'.$report->File.'">Download Attachment</a>';
		$data['IsView'] = 0;
		$data['Status'] = 'sent';
		$data['CreatedDate'] = date('Y-m-d H:i:s');
		$data['SentDate'] = date('Y-m-d H:i:s');
		$mailbox = new Mailbox();
		$mailbox->insertRow($data,null);
		$data['Status'] = 'inbox';
		$mailbox->insertRow($data,null);
		$notif = array(
			'url'   => url('mailbox'),
			'userid'    => $request->input('DoctorID'),
			'title'     => 'You Have Got New Inbox ',
			'note'      => 'You Have Got New Inbox , Please check your mailbox',
			'entry_by'	=> $request->input('DoctorID'),
		);
		\SximoHelpers::storeNote($notif);
		return Redirect::to('report')->with('messagetext',\Lang::get('Report Mailed Successfully'))->with('msgstatus','success');

	}

	function mail_attachment(Request $request,$report,$user,$subject) {
		$file = url('uploads/Reports').'/'.$report->File;

		Mail::raw($request->input('message'),function ($m) use ($user,$file,$subject,$report)  {
			$m->to($user->email)->subject("[ " .CNF_APPNAME." ] ".$subject);
			$m->attach($file,['as' => $report->Title.'.'.pathinfo($file, PATHINFO_EXTENSION)]);
		});



	}


}