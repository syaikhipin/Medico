<?php  namespace App\Http\Controllers;
use App\ClinicFeedback;
use App\DoctorFeedback;
use App\Models\Sponsorship;
use App\Payment;
use Illuminate\Http\Request;
use \App\Models\Clinic;
use \App\Models\Clinicschedule;
use \App\Models\Doctor;
use \App\Models\Scheduledetail;
use \App\User;
use \App\Speciality;
use \App\Expertize;


use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use kamranahmedse\Geocode;
use Stripe\Customer;
use Stripe\Stripe;
use Validator, Input, Redirect ;

class HomeController extends Controller {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index( Request $request )
	{


		if(CNF_FRONT =='false' && $request->segment(1) =='' ) :
			return Redirect::to('dashboard');
		endif;

		$page = $request->segment(1);
		if($page !='') :
			$content = \DB::table('tb_pages')->where('alias','=',$page)->where('status','=','enable')->get();
			//return '';
			if(count($content) >=1)
			{

				$row = $content[0];
				$this->data['pageTitle'] = $row->title;
				$this->data['pageNote'] = $row->note;
				$this->data['pageMetakey'] = ($row->metakey !='' ? $row->metakey : CNF_METAKEY) ;
				$this->data['pageMetadesc'] = ($row->metadesc !='' ? $row->metadesc : CNF_METADESC) ;

				$this->data['breadcrumb'] = 'active';

				if($row->access !='')
				{
					$access = json_decode($row->access,true)	;
				} else {
					$access = array();
				}

				// If guest not allowed 
				if($row->allow_guest !=1)
				{
					$group_id = \Session::get('gid');
					$isValid =  (isset($access[$group_id]) && $access[$group_id] == 1 ? 1 : 0 );
					if($isValid ==0)
					{
						return Redirect::to('')
							->with('message', \SiteHelpers::alert('error',Lang::get('core.note_restric')));
					}
				}
				if($row->template =='backend')
				{
					$page = 'pages.'.$row->filename;
				} else {
					$page = 'layouts.'.CNF_THEME.'.index';
				}
				//print_r($this->data);exit;

				$filename = base_path() ."/resources/views/pages/".$row->filename.".blade.php";

				if(file_exists($filename))
				{
					$this->data['pages'] = 'pages.'.$row->filename;
					if($this->data['pages']=='pages.result')
						$this->data['detail']=$this->fetchResult($request);

					return view($page,$this->data);
				} else {
					return Redirect::to('')
						->with('message', \SiteHelpers::alert('error',\Lang::get('core.note_noexists')));
				}

			} else {
				return Redirect::to('')
					->with('message', \SiteHelpers::alert('error',\Lang::get('core.note_noexists')));
			}


		else :
			$this->data['pageTitle'] = 'Home';
			$this->data['pageNote'] = 'Welcome To Our Site';
			$this->data['breadcrumb'] = 'inactive';
			$this->data['pageMetakey'] =  CNF_METAKEY ;
			$this->data['pageMetadesc'] = CNF_METADESC ;
			$this->data['specialities'] = Speciality::all() ;
			$this->data['expertizes'] = Expertize::all();

			$this->data['pages'] = 'pages.home';
			$page = 'layouts.'.CNF_THEME.'.index';
			return view($page,$this->data);
		endif;


	}

	public function  getLang($lang='en')
	{
		\Session::put('lang', $lang);
		return  Redirect::back();
	}

	public function  getSkin($skin='sximo')
	{
		\Session::put('themes', $skin);
		return  Redirect::back();
	}



	public  function  postContact( Request $request)
	{

		$this->beforeFilter('csrf', array('on'=>'post'));
		$rules = array(
			'name'		=>'required',
			'subject'	=>'required',
			'message'	=>'required|min:20',
			'sender'	=>'required|email'
		);
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes())
		{

			$data = array('name'=>$request->input('name'),'sender'=>$request->input('sender'),'subject'=>$request->input('subject'),'notes'=>$request->input('message'));
			$message = view('emails.contact', $data);

			$to 		= 	CNF_EMAIL;
			$subject 	= $request->input('subject');
			$headers  	= 'MIME-Version: 1.0' . "\r\n";
			$headers 	.= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers 	.= 'From: '.$request->input('name').' <'.$request->input('sender').'>' . "\r\n";
			//mail($to, $subject, $message, $headers);

			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('success','Thank You , Your message has been sent !'));

		} else {
			return Redirect::to($request->input('redirect'))->with('message', \SiteHelpers::alert('error','The following errors occurred'))
				->withErrors($validator)->withInput();
		}
	}
	public function fetchResult($request){

		$type = $request->input('type');
		$q = $request->input('q');
		$loc = $request->input('loc');
		$scope= $request->input('scope');
		$doctors = array();
		$day = array();
		if(Input::has('min'))
		{

			$min = Input::get('min');
			$max = Input::get('max');
			$locality = Input::get('area');
			$doctors = Doctor::whereBetween('Fee',[$min,$max])->lists('DoctorID');

			if(Input::get('day')!="")
				$day = explode(',',Input::get('day'));
			$sort = Input::get('sort');


			if(!empty($day))
			{

				if(empty($locality))
				{
					$data = Clinic::join('tb_clinic_schedule','tb_clinic_schedule.ClinicID','=','tb_clinic.ClinicID')
						->where('tb_clinic.isDelete',0)
						->join('tb_Schedule_Detail','tb_Schedule_Detail.ScheduleID','=','tb_clinic_schedule.ScheduleID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->whereIn('tb_Schedule_Detail.Day',$day)
						->select('tb_clinic.ClinicID as ClinicID','DoctorID');
				}
				else{
					$data = Clinic::join('tb_clinic_schedule','tb_clinic_schedule.ClinicID','=','tb_clinic.ClinicID')
						->where('tb_clinic.isDelete',0)
						->join('tb_Schedule_Detail','tb_Schedule_Detail.ScheduleID','=','tb_clinic_schedule.ScheduleID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->whereIn('tb_clinic.Locality',$locality)
						->whereIn('tb_Schedule_Detail.Day',$day)
						->select('tb_clinic.ClinicID as ClinicID','DoctorID');

				}

				$clinics = $data->lists('ClinicID');
				$doctors = $data->lists('DoctorID');

			}
			else
			{

				if(empty($locality))
				{
					$data = Clinic::join('tb_clinic_schedule','tb_clinic_schedule.ClinicID','=','tb_clinic.ClinicID')
						->where('tb_clinic.isDelete',0)
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->select('tb_clinic.ClinicID as ClinicID','DoctorID');
				}
				else {
					$data = Clinic::join('tb_clinic_schedule', 'tb_clinic_schedule.ClinicID', '=', 'tb_clinic.ClinicID')
						->where('tb_clinic.isDelete',0)
						->whereIn('tb_clinic_schedule.DoctorID', $doctors)
						->whereIn('tb_clinic.Locality',$locality)
						->select('tb_clinic.ClinicID as ClinicID','DoctorID');
				}

				$clinics = $data->distinct('ClinicID')->lists('ClinicID');
				$doctors = $data->distinct('tb_clinic_schedule.DoctorID')->lists('DoctorID');

			}
		}

		if($type=='Clinic')
		{
			if($q!='' || $loc!=''){
				if($scope=='Speciality'){
					if($loc!=''){
						if(Input::has('min'))
						{
							$result['clinics'] = Clinic::where('Speciality','Like','%'.$q.'%')
								->where('City','Like','%'.$loc.'%')
								->whereIn('ClinicID',$clinics)
								->orderBy('isSponsored','desc')->paginate(10);
						}
						else{
							$result['clinics'] = Clinic::where('Speciality','Like','%'.$q.'%')
								->where('City','Like','%'.$loc.'%')
								->where('isDelete',0)
								->orderBy('isSponsored','desc')->paginate(10);
						}
					}
					else{
						if(Input::has('min')) {
							$result['clinics'] = Clinic::where('Speciality', 'Like', '%' . $q . '%')
								->whereIn('ClinicID',$clinics)
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
						else{
							$result['clinics'] = Clinic::where('Speciality','Like','%'.$q.'%')
								->where('isDelete',0)
								->orderBy('isSponsored','desc')->paginate(10);
						}

					}
				}
				elseif($scope=='Clinic'){
					if($loc!='') {
						if(Input::has('min')) {
							$result['clinics'] = Clinic::where('Name', 'Like', '%' . $q . '%')
								->where('City', 'Like', '%' . $loc . '%')
								->whereIn('ClinicID',$clinics)
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
						else{
							$result['clinics'] = Clinic::where('Name', 'Like', '%' . $q . '%')
								->where('City', 'Like', '%' . $loc . '%')
								->where('isDelete',0)
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
					}
					else{
						if(Input::has('min')) {
							$result['clinics'] = Clinic::where('Name', 'Like', '%' . $q . '%')
								->whereIn('ClinicID',$clinics)
								->orderBy('isSponsored', 'desc')->paginate(10);
						}else {
							$result['clinics'] = Clinic::where('Name', 'Like', '%' . $q . '%')
								->where('isDelete',0)
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
					}
				}
				else{
					if($loc!='') {
						if($q!='') {
							if(Input::has('min')) {
								$result['clinics'] = Clinic::where('City', 'like', '%' . $loc . '%')
									->whereIn('ClinicID',$clinics)
									->where(function ($query) use ($q) {
										$query->where('Name', 'like', '%' . $q . '%')
											->orwhere('Speciality', 'like', '%' . $q . '%');
									})->orderBy('isSponsored', 'desc')->paginate(10);
							}
							else{
								$result['clinics'] = Clinic::where('City', 'like', '%' . $loc . '%')->where('isDelete',0)
									->where(function ($query) use ($q) {
									$query->where('Name', 'like', '%' . $q . '%')
										->orwhere('Speciality', 'like', '%' . $q . '%');
								})->orderBy('isSponsored', 'desc')->paginate(10);
							}
						}
						else{
							if(Input::has('min')) {
								$result['clinics'] = Clinic::where('City', 'like', '%' . $loc . '%')
									->whereIn('ClinicID',$clinics)
									->orderBy('isSponsored', 'desc')->paginate(10);
							}
							else{
								$result['clinics'] = Clinic::where('City', 'like', '%' . $loc . '%')
									->where('isDelete',0)
									->orderBy('isSponsored', 'desc')->paginate(10);
							}
						}

					}
					else{
						if(Input::has('min')) {
							$result['clinics'] = Clinic::where('Name', 'like', '%' . $q . '%')->whereIn('ClinicID',$clinics)
								->orwhere('Speciality', 'like', '%' . $q . '%')->orderBy('isSponsored', 'desc')->paginate(10);
						}
						else{
							$result['clinics'] = Clinic::where('Name', 'like', '%' . $q . '%')
								->where('isDelete',0)
								->orwhere('Speciality', 'like', '%' . $q . '%')
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
					}


				}
				$result['count'] = $result['clinics']->total();
				if($loc=="")
				$result['locations'] = $result['clinics']->unique('Locality')->lists('Locality');
				else {
					$result['locations']  = Clinic::where('City','like','%'.$loc.'%')->where('isDelete',0)->distinct('Locality')->lists('Locality');
				}

//if($result['count']==0){
//					$result['request'] = $request;
//					return $result;
//				}

				$result['request'] = $request;
				return $result;
			}
			else{
				$result['count']= 0;
				$result['request'] = $request;
				return $result;
			}
		}
		else
		{

			if($q!='' || $loc!=''){
				if($scope=='Expertization'){
					if($loc!=''){
						if(Input::has('min')) {
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('DoctorID',$doctors)
								->where('Expertization', 'like', '%' . $q . '%')
								->where('Cities', 'like', '%' . $loc . '%')
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
						else{
							$result['doctors'] = \DB::table('tb_doctor')
								->where('Expertization', 'like', '%' . $q . '%')
								->where('Cities', 'like', '%' . $loc . '%')
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
					}
					else{
						if(Input::has('min')) {
							$result['doctors'] = \DB::table('tb_doctor')->whereIn('DoctorID',$doctors)
								->where('Expertization', 'Like', '%' . $q . '%')
								->orderBy('isSponsored', 'desc')->paginate(10);
						}else{
							$result['doctors'] = \DB::table('tb_doctor')->where('Expertization','Like','%'.$q.'%')
								->orderBy('isSponsored','desc')->paginate(10);
						}
					}
				}
				elseif($scope=='Doctor'){
					$q = explode(' ',$q);
					if(count($q)==1){
						$q[1] = $q[0];
					}
					//dd($request->all());
					if($loc!=""){
						$ids = User::where('group_id', '=', 3)
							->where(function ($query) use ($q) {
								$query->where('first_name', 'like', '%' . $q[0] . '%')
									->orwhere('last_name', 'like', '%' . $q[1] . '%');
							})->lists('id');

						if(Input::has('min')){
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('UserID',$ids)
								->whereIn('DoctorID',$doctors)
								->where('Cities','like','%'.$loc.'%')
								->orderBy('isSponsored','desc')->paginate(10);
						}
						else{
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('UserID',$ids)
								->where('Cities','like','%'.$loc.'%')
								->orderBy('isSponsored','desc')->paginate(10);
						}



					}
					else{
						$ids = User::where('group_id', '=', 3)->where(function ($query) use ($q) {
							$query->where('first_name', 'like', '%' . $q[0] . '%')
								->orwhere('last_name', 'like', '%' . $q[1] . '%');
						})->lists('id');
						if(Input::has('min')){
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('UserID', $ids)
								->whereIn('DoctorID',$doctors)
								->orderBy('isSponsored','desc')->paginate(10);
						}
						else{
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('UserID', $ids)
								->orderBy('isSponsored','desc')->paginate(10);
						}

					}
				}
				else{
					if($loc!='') {
						if($q!=''){
							$q = explode(' ',$q);
							if(count($q)==1){
								$q[1] = $q[0];
							}
							if(Input::has('min')) {

								$result['doctors'] = \DB::table('tb_doctor')
									->whereIn('DoctorID',$doctors)
										->join('tb_users', 'tb_doctor.DoctorID', '=', 'tb_users.id')
										->where('tb_doctor.Cities', 'like', '%' . $loc . '%')
										->where(function ($query) use ($q) {
											$query->where('tb_users.first_name', 'like', '%' . $q[0] . '%')
												->orWhere('tb_users.last_name', 'like', '%' . $q[1] . '%')
												->orWhere('tb_doctor.Expertization', 'like', '%' . $q[0] . '%');
										})
										->orderBy('isSponsored', 'desc')->paginate(10);
							}
							else{
								$result['doctors'] = \DB::table('tb_doctor')
									->join('tb_users', 'tb_doctor.DoctorID', '=', 'tb_users.id')
									->where('tb_doctor.Cities', 'like', '%' . $loc . '%')

									->where(function ($query) use ($q) {
										$query->where('tb_users.first_name', 'like', '%' . $q[0] . '%')
											->orWhere('tb_users.last_name', 'like', '%' . $q[1] . '%')
											->orWhere('tb_doctor.Expertization', 'like', '%' . $q[0] . '%');
									})
									->orderBy('isSponsored', 'desc')->paginate(10);
							}
						}
						else{
							if(Input::has('min'))
							{
								$result['doctors'] = \DB::table('tb_doctor')
									->whereIn('DoctorID',$doctors)
									->where('Cities','like','%'.$loc.'%')
									->orderBy('isSponsored','desc')->paginate(10);
							}else{
								$result['doctors'] = \DB::table('tb_doctor')
									->where('Cities','like','%'.$loc.'%')
									->orderBy('isSponsored','desc')->paginate(10);
							}
						}

					}
					else{
						$q = explode(' ',$q);
						if(count($q)==1){
							$q[1] = $q[0];
						}
						if(Input::has('min')) {
							$result['doctors'] = \DB::table('tb_doctor')
								->whereIn('DoctorID',$doctors)
								->join('tb_users', 'tb_doctor.UserID', '=', 'tb_users.id')
								->where(function ($query) use ($q) {
									$query->where('tb_users.first_name', 'like', '%' . $q[0] . '%')
										->orWhere('tb_users.last_name', 'like', '%' . $q[1] . '%')
										->orWhere('tb_doctor.Expertization', 'like', '%' . $q[0] . '%');
								})
								->orderBy('isSponsored', 'desc')->paginate(10);
						}else{
							$result['doctors'] = \DB::table('tb_doctor')
								->join('tb_users', 'tb_doctor.UserID', '=', 'tb_users.id')
								->where(function ($query) use ($q) {
									$query->where('tb_users.first_name', 'like', '%' . $q[0] . '%')
										->orWhere('tb_users.last_name', 'like', '%' . $q[1] . '%')
										->orWhere('tb_doctor.Expertization', 'like', '%' . $q[0] . '%');
								})
								->orderBy('isSponsored', 'desc')->paginate(10);
						}
					}


				}
				$result['count'] = $result['doctors']->total();
				if($loc=="")
				$locations = \DB::table('tb_clinic')->join('tb_clinic_schedule','tb_clinic.ClinicID','=','tb_clinic_schedule.ClinicID')
										->whereIn('tb_clinic_schedule.DoctorID',$result['doctors']->lists('DoctorID'))->lists('Locality');
				else{
					$locations  = Clinic::where('City','like','%'.$loc.'%')->lists('Locality');
				}
				$result['locations'] = ($locations);
				$result['request'] = $request;
				return $result;
			}
			else{
				$result['count']= 0;
				$result['request'] = $request;
				return $result;
			}
		}



//
//
//
//		if($q!=''|| $loc!=''){
//			if($q==''){
//				if($type=='Clinic'){
//					$result['clinics'] = Clinic::where('City', 'like', '%'.$loc.'%')->orwhere('Address', 'like', '%'.$loc.'%')->get();
//					return $result;
//				}
//				if($type=='Doctor'){
//					$ids= User::where('group_id','=',3)->where('City','like','%'.$loc.'%')->lists('id');
//					$result['doctors']=\DB::table('tb_doctor')->whereIn('UserID',$ids)->get();
//					return $result;
//				}else{
//					return 'No Result Found';
//				}
//			}
//
//			if($loc==''){
//				if($type=='Clinic'){
//					$result['clinics'] = Clinic::where('Name','like','%'.$q[0].'%')
//						->orwhere('Speciality','like','%'.$q[1].'%')->get();
//					return $result;
//
//				}
//				if($type=='Doctor'){
//					$ids= User::where('group_id','=',3)->where(function ($query) use ($q) {
//						$query->where('first_name','like','%'.$q.'%')
//							->orwhere('last_name','like','%'.$q.'%');})->lists('id');
//					$result['doctors'] = \DB::table('tb_doctor')->whereIn('UserID',$ids)->orWhere('Expertization','like',"%".$q."%")->get();
//
//					return $result;
//				}
//			}
//			if($type=='Clinic'){
//				$result['clinics'] = Clinic::where(function($query) use ($loc){ $query->where('City', 'like', '%'.$loc.'%')->orwhere('Address', 'like', '%'.$loc.'%');})->where(function ($query) use ($q) {
//					$query->where('Name','like','%'.$q[0].'%')
//						->orwhere('Speciality','like','%'.$q[1].'%');
//				})->get();
//
//				return $result;
//
//			}
//			if($type=='Doctor'){
//				$ids= User::where('group_id','=',3)->where('City','like','%'.$loc.'%')->where(function ($query) use ($q) {
//					$query->where('first_name','like','%'.$q.'%')
//						->orwhere('last_name','like','%'.$q.'%');})->lists('id');
//				$result['doctors'] = \DB::table('tb_doctor')->whereIn('UserID',$ids)->orWhere('Expertization','like',"%".$q."%")->get();
//				return $result;
//			}
//		}else{
//			return "No Parameters Found";
//		}
//
//		return 'No Result Found';
	}

	public function getClinicDetail($id)
	{
		$this->data['pageTitle'] = Clinic::where('ClinicID',$id)->pluck('Name');
		$this->data['pageNote'] = Clinic::where('ClinicID',$id)->pluck('Description');
		$this->data['pageMetakey'] =  CNF_METAKEY ;
		$this->data['pageMetadesc'] = CNF_METADESC ;
		$this->data['breadcrumb'] = 'active';
		$access = array();
		$page = 'layouts.'.CNF_THEME.'.index';
		$this->data['id'] = $id;
		$this->data['pages']= 'pages.clinicnew';
		$this->data['feedback'] = \DB::select('select *  from tb_clinic_feedback cf where created_at = (select max(created_at) from tb_clinic_feedback cf1 where cf1.FromUserID = cf.FromUserID) and ToClinicID = '.$id);
		$this->data['scheduledata'] = ClinicSchedule::where('ClinicID',$id)->paginate(5);
		foreach($this->data['scheduledata'] as $sch){
			$sch['scheduledetail'] = $sch->ScheduleDetail();
		}
		return view($page,$this->data);
	}

	public function getDoctorDetail($id){
		$this->data['pageTitle'] = \SiteHelpers::gridDisplayView($id,'DoctorID','1:tb_doctor,tb_users:DoctorID:first_name|last_name',"id = tb_doctor.UserID");
		$this->data['pageNote'] = Doctor::where('DoctorID',$id)->pluck('Expertization');
		$this->data['pageMetakey'] =  CNF_METAKEY ;
		$this->data['pageMetadesc'] = CNF_METADESC ;
		$this->data['breadcrumb'] = 'active';
		$access = array();
		$page = 'layouts.'.CNF_THEME.'.index';
		$this->data['id'] = $id;
		$this->data['pages']= 'pages.doctornew';
		$this->data['feedback'] = \DB::select('select *  from tb_doctor_feedback cf where created_at = (select max(created_at) from tb_doctor_feedback cf1 where cf1.FromUserID = cf.FromUserID) and ToDoctorID = '.$id);
		$this->data['schedule'] = ClinicSchedule::where('DoctorID',$id)->get();
		foreach($this->data['schedule'] as $sch){
			$sch['scheduledetail'] = $sch->ScheduleDetail();
		}
		return view($page,$this->data);
	}

	public function autocomplete(Request $request){

		if($request->ajax()==true) {
			$type = $request->input('type');
			$term = $request->input('term');
			$location = $request->input('location');

			$results = array();
			if ($type == 'Clinic') {
				if ($location != '') {
					$queries = \DB::table('tb_clinic')
						->where('Name', 'LIKE', '%' . $term . '%')
						->where('City', 'LIKE', '%' . $location . '%')
						->where('isDelete',0)
						->get();
				} else {
					$queries = \DB::table('tb_clinic')
						->where('Name', 'LIKE', '%' . $term . '%')
						->where('isDelete',0)
						->get();
				}

				foreach ($queries as $query) {
					$results[] = ['value' => $query->Name, 'type' => 'Clinic'];
				}

				if ($location != '') {
					$queries = \DB::table('tb_clinic')
						->where('Speciality', 'LIKE', '%' . $term . '%')
						->where('City', 'LIKE', '%' . $location . '%')
						->where('isDelete',0)
						->get();
				} else {
					$queries = \DB::table('tb_clinic')
						->where('Speciality', 'LIKE', '%' . $term . '%')
						->where('isDelete',0)
						->get();
				}
				$speciality = array();
				foreach ($queries as $query) {
					$specialities = explode(',', $query->Speciality);

					foreach ($specialities as $spec) {

						if (stripos($spec, $term) !== false) {
							$speciality[] = str_replace(' ', '', $spec);
						}
					}
				}
				$speciality = array_unique(array_map('strtolower', $speciality), SORT_REGULAR);
				foreach ($speciality as $s) {
					$results[] = ['value' => ucwords($s), 'type' => 'Speciality'];
				}

			} else {
				if ($location != "") {
					$ids = User::where('group_id', '=', 3)
						//->where('City', 'like', '%' . $location . '%')
						->where(function ($query) use ($term) {
							$query->where('first_name', 'like', '%' . $term . '%')
								->orwhere('last_name', 'like', '%' . $term . '%');
						})->lists('id');
					$queries = \DB::table('tb_users')
						->join('tb_doctor', 'tb_users.id', '=', 'tb_doctor.UserID')
						->whereIn('tb_users.id', $ids)
						->where('Cities','like','%' . $location . '%')
						->get();
				} else {
					$ids = User::where('group_id', '=', 3)->where(function ($query) use ($term) {
						$query->where('first_name', 'like', '%' . $term . '%')
							->orwhere('last_name', 'like', '%' . $term . '%');
					})->lists('id');
					$queries = \DB::table('tb_users')
						->join('tb_doctor', 'tb_users.id', '=', 'tb_doctor.UserID')
						->whereIn('tb_users.id', $ids)
						->get();
				}



				foreach ($queries as $query) {
					$results[] = ['value' => $query->first_name . ' ' . $query->last_name, 'type' => 'Doctor'];
				}
				if ($location != "") {
					$queries = \DB::table('tb_doctor')
						//->join('tb_doctor', 'tb_users.id', '=', 'tb_doctor.UserID')
						->where('Expertization', 'like', '%' . $term . '%')
						->where('Cities', 'like', '%' . $location . '%'                                                                      )
						->get();
				} else {
					$queries = \DB::table('tb_doctor')
						->where('Expertization', 'like', '%' . $term . '%')
						->get();
				}


				$expertization = array();
				foreach ($queries as $query) {
					$specialities = explode(',', $query->Expertization);
					foreach ($specialities as $expert) {
						if (stripos($expert, $term) !== false) {
							$expertization[] = str_replace(' ', '', $expert);
						}
					}
					$expertization = array_unique(array_map('strtolower', $expertization), SORT_REGULAR);
				}

				foreach ($expertization as $exp) {
					$results[] = ['value' => ucwords($exp), 'type' => 'Expertization'];
				}
			}
			return response()->json($results);
		}else{
			echo "Oops You can't access this page";
		}
	}


	public function searchlocation(Request $request )
	{
			if ($request->ajax() == true) {
		$type = $request->input('type');
		$term = $request->input('term');

		$results = array();
		if ($type == 'Clinic') {
			$cities = \DB::table('tb_clinic')
				->where('City', 'LIKE', '%' . $term . '%')
				->where('isDelete',0)
				->lists('City');

			$cities = array_unique(array_map('strtolower', $cities), SORT_REGULAR);

			foreach ($cities as $city) {
				$results[] = ['value' => ucwords($city)];
			}
		} else {

			$cities = \DB::table('tb_doctor')
				->where('Cities', 'like', '%' . $term . '%')
				->lists('Cities');
			$citylist = array();
			foreach($cities as $city){
				$c = explode(',',$city);
				foreach($c as $city){
					if (stripos($city, $term) !== false) {
						$citylist[] = str_replace(' ', '', $city);
					}
				}

			}

			$citylist = array_unique(array_map('strtolower', $citylist), SORT_REGULAR);
			foreach ($citylist as $city) {
				$results[] = ['value' => ucwords($city)];

			}
		}
		return response()->json($results);
			}
		else{
			echo "Oops You can't access this page";
		}
	}

	public function expire()
	{

		$users = User::where('group_id','=','3')->where('payment_method',"!=","0")->get();
		foreach($users as $user) {
			if ($user->payment_method == 'paypal') {
				$expire = Payment::where('user_id', '=', $user->id)->first()->expires;

			} else {
				Stripe::setApiKey(CNF_STRIPE_API_KEY);
				$customer = Customer::retrieve($user->stripe_id);
				$subscription = $customer->subscriptions->retrieve($user->stripe_subscription);
				$expire = date('Y-m-d H:i:s', $subscription["current_period_end"]);

			}

			$subject = "[ " . CNF_APPNAME . " ] Subscription Expiration Mail ";
			$user['expire'] = date('Y-M-d',strtotime($expire));
			if (strtotime($expire) - time() <= 259200 && strtotime($expire) - time() > 0 ) {

				Mail::send('user.emails.expire',['user' => $user],function($m) use ($subject,$user){
					$m->to($user['email'], $user['first_name'] .' '.$user['last_name'])->subject($subject);
				});
			}
			if(time() > strtotime($expire)){
				Mail::send('user.emails.expired',['user' => $user],function($m) use ($subject,$user){
					$m->to($user['email'], $user['first_name'] .' '.$user['last_name'])->subject($subject);
				});
				User::where('id', '=', $user->id)->update(['payment_method' => '0']);
				User::where('id', '=', $user->id)->update(['active' => '0']);
			}
		}
	}
	public function VerifyLocation(Request $request){

		$cities =  \DB::table('tb_clinic')->where("isDelete",0)
			->lists('City');

		$cities = array_unique(array_map('strtolower', $cities), SORT_REGULAR);

		foreach ($cities as $city) {
			if($city == strtolower($request->city)){
				return ucfirst($city);
			}
		}
		return "";

	}

	public function distance($lt1,$lng1,$lt2,$lng2){

		$theta = $lng1 - $lng2;
		$dist = sin($lt1 * pi() / 180.0) * sin($lt2 * pi() / 180.0) + cos($lt1 * pi() / 180.0) * cos($lt2 * pi() / 180.0) * cos($theta * pi() / 180.0);
		$dist = acos($dist);
		$dist = $dist * 180 / pi();
		$dist = $dist * 60 * 1.1515 * 1.609344;  //conversion in kilometer
		return $dist;
	}


	public function filter(Request $request){
		$this->data['pageTitle'] = 'results';
		$this->data['pageNote'] = '';
		$this->data['pageMetakey'] =  CNF_METAKEY ;
		$this->data['pageMetadesc'] = CNF_METADESC ;
		$this->data['breadcrumb'] = 'active';
		if($request->day!=""){
			$day = explode(',',$request->day);
		}
		else
			$day = array();

		$data = explode(',',$request->data);
		if($request->type=='clinic')
		{
			$doctors = Doctor::whereBetween('Fee',[$request->min,$request->max])->lists('DoctorID');
			if(!empty($day)){

				if($request->location!=''){
					$result['clinics'] = Clinic::whereIn('tb_clinic.ClinicID',$data)
						->where('isDelete',0)
						->where('Address','like','%'.$request->location.'%')
						->join('tb_clinic_schedule', 'tb_clinic.ClinicID', '=','tb_clinic_schedule.ClinicID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->join('tb_Schedule_Detail', 'tb_clinic_schedule.ScheduleID','=', 'tb_Schedule_Detail.ScheduleID')
						->whereIn('tb_Schedule_Detail.Day',$day)
						->where('Address','like','%'.$request->location.'%')
						->groupBy('tb_clinic.ClinicID')
						->paginate(10);
				}
				else{
					$result['clinics'] = Clinic::whereIn('tb_clinic.ClinicID',$data)
						->where('isDelete',0)
						->join('tb_clinic_schedule', 'tb_clinic.ClinicID','=', 'tb_clinic_schedule.ClinicID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->join('tb_Schedule_Detail', 'tb_clinic_schedule.ScheduleID','=', 'tb_Schedule_Detail.ScheduleID')
						->whereIn('tb_Schedule_Detail.Day',$day)
						->groupBy('tb_clinic.ClinicID')
						->paginate(10);
				}
			}
			else{
				if($request->location!='') {
					$result['clinics'] = Clinic::whereIn('tb_clinic.ClinicID', $data)
						->where('isDelete',0)
						->where('Address', 'like', '%' . $request->location . '%')
						->join('tb_clinic_schedule', 'tb_clinic.ClinicID','=', 'tb_clinic_schedule.ClinicID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->groupBy('tb_clinic.ClinicID')
						->paginate(10);
				}
				else{
					$result['clinics'] = Clinic::whereIn('tb_clinic.ClinicID', $data)
						->where('isDelete',0)
						->distinct("tb_clinic.ClinicID")
						->join('tb_clinic_schedule', 'tb_clinic.ClinicID','=', 'tb_clinic_schedule.ClinicID')
						->whereIn('tb_clinic_schedule.DoctorID',$doctors)
						->groupBy('tb_clinic.ClinicID')
						->paginate(10);
				}
			}
			if(empty($result['clinics']))
			{
				return 'No data found';
			}


			$result['count']= count($result['clinics']);
			$result['request']= $request;
			$this->data['detail'] = $result;
			$this->data['pages'] = 'pages.results';
			return view( 'layouts.'.CNF_THEME.'.index' ,$this->data);
		}
		else{
			$doctors = Doctor::whereIn('DoctorID',$data)
				->whereBetween('Fee',[$request->min,$request->max])->lists('DoctorID');
			if($request->location!='') {
				$data = Clinic::where('address', 'like', '%' . $request->location . '%')->where('isDelete',0)->lists('ClinicID');
			}
			else{
				$data = array();
			}

			if(!empty($day)){
				if(empty($data)){
					$result['doctors'] = Doctor::whereIn('tb_doctor.DoctorID',$doctors)
						->leftjoin('tb_clinic_schedule', 'tb_doctor.DoctorID', '=','tb_clinic_schedule.DoctorID')
						->leftjoin('tb_Schedule_Detail', 'tb_clinic_schedule.ScheduleID','=', 'tb_Schedule_Detail.ScheduleID')
						->whereIn('tb_Schedule_Detail.Day',$day)
						->paginate(10);
				}
				else{
					$result['doctors'] = Doctor::whereIn('tb_doctor.DoctorID',$doctors)
						->leftjoin('tb_clinic_schedule', 'tb_doctor.DoctorID', '=','tb_clinic_schedule.DoctorID')
						->whereIn('tb_clinic_schedule.ClinicID',$data)
						->leftjoin('tb_Schedule_Detail', 'tb_clinic_schedule.ScheduleID','=', 'tb_Schedule_Detail.ScheduleID')
						->whereIn('tb_Schedule_Detail.Day',$day)
						->paginate(10);
				}

			}
			else{

				if(!empty($data)){
					$result['doctors'] = Doctor::whereIn('tb_doctor.DoctorID',$doctors)
						->leftjoin('tb_clinic_schedule', 'tb_doctor.DoctorID', '=','tb_clinic_schedule.DoctorID')
						->whereIn('tb_clinic_schedule.ClinicID',$data)
						->paginate(10);
				}
				else{
					$result['doctors'] = Doctor::whereIn('tb_doctor.DoctorID',$doctors)->paginate(10);
				}


			}

			if(empty($result['doctors']))
			{
				return 'No data found';
			}

			$result['doctors'] = $result['doctors']->unique('DoctorID');
			$result['count']= count($result['doctors']);
			$result['request']= $request;
			$this->data['detail'] = $result;
			$this->data['pages'] = 'pages.results';
			return view( 'layouts.'.CNF_THEME.'.index' ,$this->data);
		}

	}
	public  function sponserAlertforClinic(){
		 $clinics = Sponsorship::where('ClinicID','!=',0)->where('EndDate','<',date('Y-m-d'))->lists('ClinicID');
		Clinic::whereIn('ClinicID',$clinics)->update(['isSponsored' => 0]);
		 $clinics = Sponsorship::where('ClinicID','!=',0)->whereBetween('EndDate',[date('Y-m-d'),date('Y-m-d',strtotime('+'.CNF_SPONSORSHIP_ALERT_DAYS.' day',strtotime(date('Y-m-d'))))])->get();

		foreach($clinics as $clinic)
		{
			$user = User::find($clinic->entry_by);
			Mail::send('emails.alert.clinic', ['clinic' => $clinic ] , function ($m) use ($user)  {
					$m->to($user['email'], $user['firstname'] .' '.$user['lastname'])->subject("[ " .CNF_APPNAME." ] Alert for Sponsorship Plan ");
			});

		}
	}

	public  function sponserAlertforDoctor(){
		$doctors = Sponsorship::where('DoctorID','!=',0)->where('EndDate','<',date('Y-m-d'))->lists('DoctorID');
		Doctor::whereIn('DoctorID',$doctors)->update(['isSponsored' => 0]);
		$doctors = Sponsorship::where('DoctorID','!=',0)->whereBetween('EndDate',[date('Y-m-d'),date('Y-m-d',strtotime('+'.CNF_SPONSORSHIP_ALERT_DAYS.' day',strtotime(date('Y-m-d'))))])->get();
		foreach($doctors as $doctor)
		{
			$user = User::find($doctor->entry_by);
			Mail::send('emails.alert.doctor', ['doctor' => $doctor ], function ($m) use ($user)  {
				$m->to($user['email'], $user['firstname'] .' '.$user['lastname'])->subject("[ " .CNF_APPNAME." ] Alert for Sponsorship Plan ");
			});

		}
	}

	public function getCron($val){
		if($val=="daily_schedule"){
			$this->expire();
			$this->sponserAlertforClinic();
			$this->sponserAlertforDoctor();
		}
		else{
			return redirect('');
		}

	}



}


