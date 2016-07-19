<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Calendar;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 
use Socialize;

class CalendarController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'calendar';
	static $per_page	= '10';

	public function __construct()
	{
		
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Calendar();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'calendar',
			'return'	=> self::returnUrl()
			
		);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');	
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any
		
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		return view('calendar.index',$this->data);
	}	

	function getJsondata( Request $request)
	{
		if (is_null($request->get('start')) || is_null($request->get('end'))) {
			die("Please provide a date range.");
		}
		
		$results = $this->model->getRows( $params = array() );

		$data = array();
		foreach($results['rows'] as $row)
		{
			if($row->entry_by==\Session::get('uid')){
				$data[] = array(
					'id'	=> $row->id,
					'title'	=> $row->title,
					'start'	=> $row->start,
					'end'	=> $row->end,
					'description'	=> $row->description
				);
			}

		}	
	
		return json_encode($data);
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
				
		$row = $this->model->where('entry_by','=',\Session::get('uid'))->find($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('calendar'); 
		}

		
		$this->data['id'] = $id;
		return view('calendar.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('calendar'); 
		}
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('calendar.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_calendar');
			
			$id = $this->model->insertRow($data , $request->input('id'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'calendar/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'calendar?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('calendar')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}	

	function postSavedrop( Request $request)
	{
		$data = $this->validatePost('calendar');
		$ID = $this->model->insertRow($data , $request->get('id'));
		return 'success';
		
	}		

	public function postDelete( Request $request)
	{
		
		if($this->access['is_remove'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');
		// delete multipe rows 
		if(count($request->input('id')) >=1)
		{
			$this->model->destroy($request->input('id'));
			
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('id'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('calendar')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('calendar')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}
	public function getImport(Request $request,$social = null)
	{
		if($request->get('calid'))
		{
			\Session::put("calid",$request->get('calid'));
		}else
		{
			\Session::put("calid",'primary');
		}
		return Socialize::with($social)->redirect('calendar');
	}



}