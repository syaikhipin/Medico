<?php namespace App\Http\Controllers;

use App\ClinicFeedback;
use App\ClinicRecommendation;
use App\Http\Controllers\controller;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Cards;
use App\Models\Appointment;
use App\Models\Sponsorship;
use App\Models\Sponsorshipplan;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use kamranahmedse\Geocode;
use Stripe\Charge;
use Stripe\Stripe;
use Validator, Input, Redirect ;


class ClinicController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();
	public $module = 'clinic';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Clinic();
		$this->modelview = new  \App\Models\Staff();
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);

		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'clinic',
			'pageUrl'			=>  url('clinic'),
			'return'	=> self::returnUrl()

		);
	//	$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'][0] : array());
		Stripe::setApiKey(CNF_STRIPE_API_KEY);
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0)
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'ClinicID');
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
			'params'	=> $filter.' AND isDelete= 0',
			'global'	=> (isset($this->access['is_global']) ? $this->access['is_global'] : 0 )
		);


		// Get Query 
		$results = $this->model->getRows( $params );

		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
		$pagination->setPath('clinic');

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

		return view('clinic.index',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_clinic');
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		//$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		$this->data['row']['Speciality'] = explode(',',$this->data['row']['Speciality']);
		$this->data['specialities'] = \DB::table('tb_speciality')->lists('Speciality','Speciality');
		return view('clinic.form',$this->data);

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
			$this->data['row'] = $this->model->getColumnTable('tb_clinic');
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		//$this->data['subgrid'] = $this->detailview($this->modelview ,  $this->data['subgrid'] ,$id );
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('clinic.view',$this->data);
	}

	function postSave( Request $request)
	{

		$rules = $this->validateForm();

		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$data = $this->validatePost('tb_clinic');
			$requestdata = $this->getLatLong($data);
			if(isset($requestdata['Speciality'])){
				foreach($requestdata['Speciality'] as $s)
				{
					$spec= Speciality::firstOrCreate(['Speciality' => $s]);
					$spec->update(['Speciality' => $s,'display_name' => $s]);

				}
				$requestdata['Speciality'] = implode(',',$requestdata['Speciality']);
			}
			$id = $this->model->insertRow($requestdata , $request->input('ClinicID'));
			if($request->bulk_Name[0]!='')
			{
				$this->detailviewsave( $this->modelview , $request->all() , $this->data['subgrid'] , $id) ;
			}

			if(!is_null($request->input('apply')))
			{
				$return = 'clinic/update/'.$id.'?return='.self::returnUrl();
			} else {
				$return = 'clinic?return='.self::returnUrl();
			}

			// Insert logs into database
			if($request->input('ClinicID') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}

			return Redirect::to($return)->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');

		} else {

			return Redirect::to('clinic/update/')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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
			//$this->model->destroy($request->input('ids'));
			$this->model->whereIn('ClinicID',$request->input('ids'))->update(['isDelete' => 1]);
			\DB::table('tb_staff')->where('ClinicID',$request->input('id'))->delete();
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('clinic')
				->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success');

		} else {
			return Redirect::to('clinic')
				->with('messagetext','No Item Deleted')->with('msgstatus','error');
		}

	}

	public function getSponsor($id){
		$this->data['row'] = Clinic::find($id);
		$this->data['plans'] = Sponsorshipplan::all();
		$this->data['cards'] = Cards::where('entry_by',\Auth::user()->id)->get();
		return (String)view('clinic.sponsor',$this->data);
	}

	public function postSponsor(Request $request,$id){
		$plan  = Sponsorshipplan::find($request->plan);
		$clinic = Clinic::find($id);
		$card =  Cards::find($request->card);
		$charge = array(
			"amount" =>	$plan->amount * 100,
			"currency" => "usd",
			"customer" => $card->cust_id,
			"card"  => $card->card_id,
			"description" => "Sponsorship charged on  plan ". $plan->name . ' for '. $clinic->Name,
		);
		$ch = Charge::create($charge);
		$duration= '+'.$plan->duration.' Month';
		$expires= date('Y-m-d', strtotime($duration));


		$sponsor = array(
			'ClinicID' => $id,
			'Plan' => $plan->id,
			'Enddate' => $expires,
			'Charge' => $ch->id,
			'entry_by' => \Auth::user()->id
		);
		$sponsorship = Sponsorship::firstOrCreate(['ClinicID' => $id]);
		$sponsorship->update($sponsor);

		$clinic->isSponsored=1;
		$clinic->save();
		return redirect('clinic');
	}

	function getLatLong($data){

		$geocode = new Geocode( $data['Address'] . ' '.$data['City'] );
		$data['Latitude']=  $geocode->getLatitude(); // returns the latitude of the address
		$data['Longitude']=  $geocode->getLongitude();
		return $data;
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
		$patients = Patient::where('entry_by',\Auth::user()->id)->lists('PatientID');
		$clinics = Appointment::whereIn('PatientID',$patients)->where('isCancelled',0)->orderBy('EndAt','desc')->lists('ClinicID','EndAt');

		$this->data['nofeedback']=[];
		$this->data['recommended']=[];
		foreach($clinics as $key => $value){
			if(strtotime($key)> time()){
				$this->data['recommended'][$value] = $value;
				$this->data['nofeedback'][$value] = $value;
			}
			else{
				unset($this->data['recommended'][ array_search($value, $this->data['nofeedback'])]);
				unset($this->data['nofeedback'][ array_search($value, $this->data['nofeedback'])]);
			}
		}

		$this->data['recommended'] = array_merge($this->data['recommended'],ClinicRecommendation::where('UserID',Auth::user()->id)->lists('ClinicID','ClinicID')->all());

		$results['rows'] = Clinic::whereIn('ClinicID',$clinics)->get();

		$results['count'] =count($results['rows']);
		// Build pagination setting
//		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;
//		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);
//		$pagination->setPath('doctor/visited');

		$this->data['rowData']		= $results['rows'];

//		 Build Pagination
//		$this->data['pagination']	= $pagination;
//		Build pager number and append current param GET
//		$this->data['pager'] 		= $this->injectPaginate();
//		Row grid Number
//		$this->data['i']			= ($page * $params['limit'])- $params['limit'];
		// Grid Configuration
		$this->data['tableGrid'] 	= $this->info['config']['grid'];
		$this->data['tableForm'] 	= $this->info['config']['forms'];
		$this->data['colspan'] 		= \SiteHelpers::viewColSpan($this->info['config']['grid']);
		// Group users permission
		$this->data['access']		= $this->access;
		// Detail from master if any

		// Master detail link if any
		//$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array());
		// Render into template
		//$this->data['pageModule'] = 'doctor/visited';


		return view('clinic.visited',$this->data);
	}

	public function getRecommend($id){
		Clinic::where('ClinicID','=',$id)->increment('Recommendation');
		$cli_recommend = array(
			'UserID' =>  Auth::user()->id,
			'ClinicID' => $id
		);
		$cr = ClinicRecommendation::firstOrCreate($cli_recommend);
		$cr->update($cli_recommend);
		return redirect('clinic/visited');
	}

	public function getFeedback(Request $request ,$id){
		if($request->ajax())
		{
			$this->data['row'] = Clinic::find($id);
			return (String)view('clinic.feedback',$this->data);
		}
		return "Oops, You can't access this page";
	}

	public function postFeedback(Request $request,$id){
		$feedback = array(
			'ToClinicID' => $id,
			'FromUserID' => \Auth::user()->id,
			'Feedback'   => $request->Feedback,
		);
		ClinicFeedback::create($feedback);
		return Redirect::to('clinic/visited')->with('messagetext',\Lang::get('Feedback submitted Successfully'))->with('msgstatus','success');
	}

	public function getLocalityautocomplete(Request $request){
		$locality = Clinic::where('City',$request->city)
			->where('Locality','like','%'.$request->term.'%')->distinct('Locality')->lists('Locality');
		$results = array() ;
		foreach ($locality as $loc) {
			$results[] = ['value' => $loc,'type'=> 'aaa'];
		}

		return $results;
	}






}