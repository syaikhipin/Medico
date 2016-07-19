<?php namespace App\Http\Controllers;

use App\DoctorFeedback;
use App\DoctorRecommendation;
use App\Feedback;
use App\Http\Controllers\controller;
use App\Models\appointment;
use App\Models\Clinicschedule;
use App\Models\patient;
use App\Models\Scheduledetail;
use App\Models\Doctor;
use App\Models\Clinic;
use App\Models\Sponsorshipplan;
use App\Models\Sponsorship;
use App\Models\Cards;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use PhpParser\Comment\Doc;
use Stripe\Charge;
use Stripe\Stripe;
use Validator, Input, Redirect ;


class DoctorController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'doctor';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Doctor();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'doctor',
			'pageUrl'			=>  url('doctor'),
			'return'	=> self::returnUrl()
			
		);
		Stripe::setApiKey(CNF_STRIPE_API_KEY);
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'DoctorID'); 
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
		$pagination->setPath('doctor');
		
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
		return view('doctor.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_doctor'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		
		$this->data['id'] = $id;
		return view('doctor.form',$this->data);
	}	

	public function getShow( $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$schedule= Clinicschedule::where('DoctorID',$id)->orderBy('ClinicID')->get();
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
			$this->data['schedule'] = $schedule;
			foreach($this->data['schedule'] as $sch){
				$schedule_detail=Scheduledetail::where('ScheduleID',$sch['ScheduleID'])->get();
				$sch['detail']=$schedule_detail;
			}
		} else {
			$this->data['row'] = $this->model->getColumnTable('tb_doctor'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('doctor.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_doctor');
			
			$id = $this->model->insertRow($data , $request->input('DoctorID'));
			
			if(!is_null($request->input('apply')))
			{
				$return = 'doctor/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'doctor?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('DoctorID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('doctor/update/'.$id)->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			return Redirect::to('doctor')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('doctor')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}



	public function postSponsor(Request $request,$id){
		$plan  = Sponsorshipplan::find($request->plan);
		$doctor = Doctor::find($id);
		$card =  Cards::find($request->card);
		$charge = array(
			"amount" =>	$plan->amount * 100,
			"currency" => "usd",
			"customer" => $card->cust_id,
			"card"  => $card->card_id,
			"description" => "Sponsorship charged on  plan ". $plan->name . ' for '. \Auth::user()->email,
		);
		$ch = Charge::create($charge);
		$duration= '+'.$plan->duration.' Month';
		$expires= date('Y-m-d', strtotime($duration));

		$sponsor = array(
		'DoctorID' => $id,
		'Plan' => $plan->id,
		'Enddate' => $expires,
		'Charge' => $ch->id,
		'entry_by' => \Auth::user()->id
		);


		$sponsorship = Sponsorship::firstOrCreate(['DoctorID' => $id]);
		$sponsorship->update($sponsor);
		$doctor->isSponsored=1;
		$doctor->save();
		return redirect('user/profile');
	}

	public function getVisited(Request $request)
	{
//		if($this->access['is_view'] ==0)
//			return Redirect::to('dashboard')
//				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

//		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'DoctorID');
//		$order = (!is_null($request->input('order')) ? $request->input('order') : 'asc');
//		// End Filter sort and order for query
//		// Filter Search for query
//		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : '');
//
//
//		$page = $request->input('page', 1);
//		$params = array(
//			'page'		=> $page ,
//			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : static::$per_page ) ,
//			'sort'		=> $sort ,
//			'order'		=> $order,
//			'params'	=> $filter,
//			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
//		);
		// Get Query
		//$results = $this->model->getRows( $params );
//		 $doctors = \DB::table('tb_doctor_patient')->where('FamilyMemberID',\Auth::user()->id)->lists('DoctorUserID');
//		$results['rows'] = Doctor::whereIn('UserID',$doctors)->get();
		$patients = Patient::where('entry_by',\Auth::user()->id)->lists('PatientID');
		$doctors = Appointment::whereIn('PatientID',$patients)->where('isCancelled',0)->orderBy('EndAt','desc')->lists('DoctorID','EndAt')->unique();
		$this->data['recommended']=[];
		$this->data['nofeedback']=[];

		foreach($doctors as $key => $value){
			if(strtotime($key)> time()){
				$this->data['recommended'][$value] = $value;
				$this->data['nofeedback'][$value] = $value;
			}
			else{
				unset($this->data['recommended'][ array_search($value, $this->data['nofeedback'])]);
				unset($this->data['nofeedback'][ array_search($value, $this->data['nofeedback'])]);
			}
		}
		$this->data['recommended'] = array_merge($this->data['recommended'],DoctorRecommendation::where('UserID',Auth::user()->id)->lists('DoctorID','DoctorID')->all());
		$results['rows'] = Doctor::whereIn('DoctorID',$doctors)->get();

		$results['count'] =count($results['rows']);
		// Build pagination setting
//		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
//		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
//		$pagination->setPath('doctor/visited');

		$this->data['rowData']		= $results['rows'];

		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);
		// Group users permission
		$this->data['access']		= $this->access;


		// Master detail link if any
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
		// Render into template
		//$this->data['pageModule'] = 'doctor/visited';


		return view('doctor.visited',$this->data);

	}

	public function getRecommend($id){
		Doctor::where('DoctorID','=',$id)->increment('Recommendation');
		$doc_recommend = array(
			'UserID' =>  Auth::user()->id,
			'DoctorID' => $id
		);
		$dr = DoctorRecommendation::firstOrCreate($doc_recommend);
		$dr->update($doc_recommend);
		return redirect('doctor/visited');
	}

	public function getFeedback(Request $request ,$id){
		if($request->ajax())
		{
			$this->data['row'] = Doctor::find($id);
			return (String)view('doctor.feedback',$this->data);
		}
		return "Oops, You can't access this page";
	}

	public function postFeedback(Request $request,$id){
		$feedback = array(
			'ToDoctorID' => $id,
			'FromUserID' => \Auth::user()->id,
			'Feedback'   => $request->Feedback,
		);
		DoctorFeedback::create($feedback);
		return Redirect::to('doctor/visited')->with('messagetext',\Lang::get('Feedback submitted Successfully'))->with('msgstatus','success');
	}


}