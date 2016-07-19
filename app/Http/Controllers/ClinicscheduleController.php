<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Clinicschedule;
use Camroncade\Timezone\Facades\Timezone;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ;
use App\Models\Clinic;
use App\Models\Doctor;


class ClinicscheduleController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'clinicschedule';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Clinicschedule();
		$this->modelview = new  \App\Models\Scheduledetail();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'clinicschedule',
			'pageUrl'			=>  url('clinicschedule'),
			'return'	=> self::returnUrl()
			
		);
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'][0] : array()); 
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'ScheduleID'); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
		// End Filter sort and order for query 
		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
		$del_clinic = Clinic::where('isDelete',1)->lists('ClinicID')->all();
		if($del_clinic){
			$filter = $filter. ' AND ClinicID not in ('.implode(',',$del_clinic).')';
		}
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
		$pagination->setPath('clinicschedule');
		
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
		return view('clinicschedule.index',$this->data);

	}	



	function getUpdate(Request $request, $id = null)
	{
		if(Doctor::where('UserID',\Auth::user()->id)->count()==0){
			return redirect('user/profile')->with('messagetext','Please Create Your Doctor Profile first')->with('msgstatus','info');
		}
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
			$this->data['row'] = $this->model->getColumnTable('tb_clinic_schedule'); 
		}

		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		return view('clinicschedule.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_clinic_schedule'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;

		return view('clinicschedule.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_clinicschedule');

			$clinic = Clinic::find($data['ClinicID']);
			$doctor = Doctor::find($data['DoctorID']);
			if($doctor->Cities!="")
			$cities = explode(',',$doctor->Cities);
			else
				$cities = array();
			array_push($cities,$clinic->City);
			$doctor->Cities = implode(',',array_unique($cities));
			$doctor->save();
			$id = $this->model->insertRow($data , $request->input('ScheduleID'));
			$requestdata= $request->all();
			foreach($requestdata['bulk_FirstSessionStart'] as $key => $morning){

				$requestdata['bulk_morning_slots'][$key]= '';
				$slot= date('H:i:s',strtotime($morning)) ;

					while(date('H:i:s',strtotime($requestdata['bulk_FirstSessionEnd'][$key])) > $slot) {
						$requestdata['bulk_morning_slots'][$key]= $requestdata['bulk_morning_slots'][$key]=='' ?  $slot : $requestdata['bulk_morning_slots'][$key].','.$slot;
						$slot =  date('H:i:s', strtotime('+'.$request->input('VisitTime').' minutes', strtotime($slot)));
					}
			}

			foreach($requestdata['bulk_SecondSessionStart'] as $key => $afternoon){
//
				$requestdata['bulk_afternoon_slots'][$key]= '';
				$slot= date('H:i:s',strtotime($afternoon)) ;
				while(date('H:i:s',strtotime($requestdata['bulk_SecondSessionEnd'][$key])) > $slot) {
					$requestdata['bulk_afternoon_slots'][$key]= $requestdata['bulk_afternoon_slots'][$key]=='' ?  $slot : $requestdata['bulk_afternoon_slots'][$key].','.$slot;
					$slot =  date('H:i:s', strtotime('+'.$request->input('VisitTime').' minutes', strtotime($slot)));
				}
			}

			$this->detailviewsave( $this->modelview , $requestdata , $this->data['subgrid'] , $id) ;


			if(!is_null($request->input('apply')))
			{
				$return = 'clinicschedule/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'clinicschedule?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('ScheduleID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('clinicschedule/update/')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			\DB::table('tb_Schedule_Detail')->where('ScheduleID',$request->input('id'))->delete();
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('clinicschedule')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('clinicschedule')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}			


}