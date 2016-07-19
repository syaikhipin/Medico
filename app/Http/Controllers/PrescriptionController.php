<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Prescription;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 


class PrescriptionController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'prescription';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Prescription();
		$this->modelview = new  \App\Models\Prescriptionmedicine();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'prescription',
			'pageUrl'			=>  url('prescription'),
			'return'	=> self::returnUrl()
			
		);
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'][0] : array()); 
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'PrescriptionID'); 
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
		if(\Auth::user()->group_id==5 || \Auth::user()->group_id==2	){
			$patients= Patient::where('entry_by',\Auth::user()->id)->lists('PatientID');
			$results['rows'] = Prescription::whereIn('PatientID',$patients)->get();
			$results['total'] = count($results['rows']);
		}
		
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('prescription');
		
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
		return view('prescription.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_prescription');
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		return view('prescription.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_prescription'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		if(\Auth::user()->group_id==5 || \Auth::user()->group_id==2){
			$this->access['is_global']=1;
		}
		$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('prescription.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_prescription');
			
			$id = $this->model->insertRow($data , $request->input('PrescriptionID'));
			$this->detailviewsave( $this->modelview , $request->all() , $this->data['subgrid'] , $id) ;
			if(!is_null($request->input('apply')))
			{
				$return = 'prescription/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'prescription?return='.self::returnUrl();
			}
			$patient = Patient::find($request->PatientID);
			// Insert logs into database
			if($request->input('PrescriptionID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');

				$notif = array(
					'url'   => url('notification'),
					'userid'    => $patient->entry_by,
					'title'     => 'New Prescription Added ',
					'note'      => 'New Prescription added for '. $patient->first_name .' '.$patient->last_name .' regarding appointment on '. date('Y-m-d h:i a',strtotime($request->StartAt)),
					'entry_by'	=> $patient->entry_by,
				);
				\SximoHelpers::storeNote($notif);
			} else {
				$notif = array(
					'url'   => url(''),
					'userid'    => $patient->entry_by,
					'title'     => 'Prescription updated ',
					'note'      => 'Prescription updated for '. $patient->first_name .' '.$patient->last_name .' regarding appointment on '. date('Y-m-d h:i a',strtotime($request->StartAt)),
					'entry_by'	=> $patient->entry_by,
				);
				\SximoHelpers::storeNote($notif);
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('prescription/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			\DB::table('tb_prescription_medicine')->where('PrescriptionID',$request->input('id'))->delete();
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('prescription')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('prescription')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}


	function postUpdate(Request $request)
	{
		$id='';
			if($this->access['is_add'] ==0 )
				return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');

			if($this->access['is_edit'] ==0 )
				return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');


		else {
			$this->data['row'] = $this->model->getColumnTable('tb_prescription');
			$this->data['row']['PatientID'] =  $request->input('PatientID');
			$this->data['row']['AppointmentID'] = $request->input('AppointmentID');
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		return view('prescription.form',$this->data);
	}

}