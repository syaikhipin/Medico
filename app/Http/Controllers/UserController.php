<?php namespace App\Http\Controllers;

use App\Expertize;
use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Models\Sponsorshipplan;
use App\Payment;
use App\User;
use App\Models\Doctor;
use App\Models\Patient;
use Camroncade\Timezone\Facades\Timezone;
use Cookie;
use Illuminate\Support\Facades\Auth;
use IpnListener;
use Mockery\CountValidator\Exception;
use Socialize;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Stripe\Customer;
use Stripe\Stripe;
use  Stripe\Error\InvalidRequest;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class UserController extends Controller {

	
	protected $layout = "layouts.main";
	protected $verified ='';
	public function __construct() {
		parent::__construct();
		Stripe::setApiKey(CNF_STRIPE_API_KEY);

	} 

	public function getRegister() {
        
		if(CNF_REGIST =='false') :    
			if(\Auth::check()):
				 return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));
			else:
				 return Redirect::to('user/login');
			  endif;
			  
		else :
				
				return view('user.register');  
		 endif ; 
           
	

	}

	public function postCreate( Request $request) {
	
		$rules = array(
			'firstname'=>'required|alpha_num|min:2',
			'lastname'=>'required|alpha_num|min:2',
			'email'=>'required|email|unique:tb_users',
			'password'=>'required|between:6,12|confirmed',
			'password_confirmation'=>'required|between:6,12'
			);	
		if(CNF_RECAPTCHA =='true') $rules['recaptcha_response_field'] = 'required|recaptcha';
				
		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			$code = rand(10000,10000000);
			
			$authen = new User;
			$authen->first_name = $request->input('firstname');
			$authen->last_name = $request->input('lastname');
			$authen->email = trim($request->input('email'));
			$authen->activation = $code;
			$authen->group_id = $request->input('type');
			$authen->password = \Hash::make($request->input('password'));
			if(CNF_ACTIVATION == 'auto') { $authen->active = '1'; } else { $authen->active = '0'; }
			$authen->save();
			
			$data = array(
				'firstname'	=> $request->input('firstname') ,
				'lastname'	=> $request->input('lastname') ,
				'email'		=> $request->input('email') ,
				'password'	=> $request->input('password') ,
				'code'		=> $code
				
			);
			if(CNF_ACTIVATION == 'confirmation')
			{ 
			
//				$to = $request->input('email');
//				$subject = "[ " .CNF_APPNAME." ] REGISTRATION ";
//				$message = view('user.emails.registration', $data);
//				$headers  = 'MIME-Version: 1.0' . "\r\n";
//				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
//					mail($to, $subject, $message, $headers);


				Mail::send('user.emails.registration', $data, function ($m) use ($data)  {
					$m->to($data['email'], $data['firstname'] .' '.$data['lastname'])->subject("[ " .CNF_APPNAME." ] REGISTRATION ");
				});

				$message = "Thanks for registering! . Please check your inbox and follow activation link";
								
			} elseif(CNF_ACTIVATION=='manual') {
				$message = "Thanks for registering! . We will validate you account before your account active";
			} else {
   			 	$message = "Thanks for registering! . Your account is active now ";         
			
			}	


			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success',$message));
		} else {
			return Redirect::to('user/register')->with('message',\SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}
	}
	
	public function getActivation( Request $request  )
	{
		$num = $request->input('code');
		if($num =='')
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		
		$user =  User::where('activation','=',$num)->get();
		if (count($user) >=1)
		{
			\DB::table('tb_users')->where('activation', $num )->update(array('active' => 1,'activation'=>''));
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Your account is active now!'));
			
		} else {
			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('error','Invalid Code Activation!'));
		}
		
		
	
	}

	public function getLogin(Request $request) {
	
		if(\Auth::check())
		{
			return Redirect::to('')->with('message',\SiteHelpers::alert('success','Youre already login'));

		} else {
			$this->data['socialize'] =  config('services');

			$this->data['next'] = $request->continue!='' ?  $request->continue : '';
			return View('user.login',$this->data);
			
		}	
	}

	public function postSignin( Request $request) {
		
		$rules = array(
			'email'=>'required|email',
			'password'=>'required',
		);		
		if(CNF_RECAPTCHA =='true') $rules['captcha'] = 'required|captcha';
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	

			$remember = (!is_null($request->get('remember')) ? 'true' : 'false' );
			
			if (\Auth::attempt(array('email'=>$request->input('email'), 'password'=> $request->input('password') ), $remember )) {
				if(\Auth::check())
				{
					$row = User::find(\Auth::user()->id); 
	
					if($row->active =='0')
					{
						// inactive 
						\Auth::logout();
						return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is not active'));
	
					} else if($row->active=='2')
					{
						// BLocked users
						\Auth::logout();
						return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is BLocked'));
					} else {
						\DB::table('tb_users')->where('id', '=',$row->id )->update(array('last_login' => date("Y-m-d H:i:s")));
						\Session::put('uid', $row->id);
						\Session::put('gid', $row->group_id);
						\Session::put('eid', $row->email);
						\Session::put('ll', $row->last_login);
						\Session::put('fid', $row->first_name.' '. $row->last_name);
						if($row->group_id=='3'){

							\Session::put('ref_id',Doctor::where('UserID',$row->id)->pluck('DoctorID'));
						}
						if($row->group_id=='5'){
							//\Session::put('ref_id',Patient::where('UserID',$row->id)->pluck('PatientID'));
						}
						if(!is_null($request->input('language')))
						{
							\Session::put('lang', $request->input('language'));	
						} else {
							\Session::put('lang', 'en');	
						}

						if($row->group_id==3)
						{
							if($row->payment_method=='0')
							{

								$trial= date('Y-m-d H:i:s', strtotime('+'.CNF_TRIAL.' day',strtotime($row->created_at)));

								if(strtotime($trial) <= strtotime(date('Y-m-d H:i:s'))) {

									return redirect('user/subscribe')->with('message', \SiteHelpers::alert('error', 'Your trial or subscription expired.'));
								}
								if($request->next!='' || Cookie::get('DoctorID')){
									if($request->next!='')
										return Redirect::to($request->next);
									return Redirect::to('appointment/book')->with('messagetext','You are in trail mode.')->with('msgstatus','success');

								}
								if(Doctor::where('UserID',$row->id)->count()==0)
								{
									return redirect('user/profile')->with('messagetext','You are in trail mode. <br/> Please Create Your Doctor Profile')->with('msgstatus','success');
								}
								return redirect('dashboard')->with('messagetext','You are in trail mode.')->with('msgstatus','success');
							}
						}

						if($request->next!='' || Cookie::get('DoctorID')){
							if($request->next!='')
								return Redirect::to($request->next);
							return Redirect::to('appointment/book');

						}
						if(CNF_FRONT =='false') :
							return Redirect::to('dashboard');						
						else :
								if($row->group_id==3 && Doctor::where('UserID',$row->id)->count()==0):
									return redirect('user/profile')->with('messagetext','Please Create Your Doctor Profile')->with('msgstatus','info');
								endif;
								if($row->group_id==5):
									return Redirect::to('');
								else:
									return Redirect::to('dashboard');
								endif;
						endif;
					}			
					
				}			
				
			} else {
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','Your username/password combination was incorrect'))
					->withInput();
			}
		} else {
		
				return Redirect::to('user/login')
					->with('message', \SiteHelpers::alert('error','The following  errors occurred'))
					->withErrors($validator)->withInput();
		}	
	}


	public function getSubscribe()
	{

		if(\Auth::check()) {

			if(\Auth::user()->group_id==3) {
				if (\Auth::user()->payment_method == '0') {
					$userid = \Auth::user()->id;
					$plans= \DB::table('tb_sub_plans')->get();
					return view('user.subscribe', compact('userid','plans'));
				}
				return redirect('dashboard')->with('messagetext', 'You already have active subscription.')->with('msgstatus', 'success');
			}
			else{
				return redirect('dashboard')->with('messagetext', 'You dont need subscription.')->with('msgstatus', 'info');
			}
		}
		else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('info', 'Your need to login first.'));
		}
	}

	public function postSubscribe(Request $request)
	{
		$user= User::find($request->input('userid'));

		if($user->stripe_id!=""){
			$customer = Customer::retrieve($user->stripe_id);
			$user->subscription($request->input('subscription'))->create($request->input('stripeToken'),[],$customer);
		}
		else{
			$user->subscription($request->input('subscription'))->create($request->input('stripeToken'));
		}
		$user->payment_method='stripe';
		$user->active=1;
		$user->save();
		return Redirect::to('dashboard')->with('messagetext', 'You have successfully subscribed.')->with('msgstatus','success');

	}


	public function getProfile() {
		
		if(!\Auth::check()) return redirect('user/login');
		$profile="";
		if(\Session::get('gid')==3){
			$profile = Doctor::where('UserID',\Auth::user()->id)->first();
		}
		if(empty($profile)){
			$profile = array(
			  'Degree' => '',
				'Expertization' => '',
				'Fee' => 0,
				'timezone' => 'UTC',
				'Experience' => '',
			);
		}
//		elseif(\Session::get('gid')==5){
//			$profile = Patient::where('UserID',\Auth::user()->id)->first();
//		}
		$info =	User::find(\Auth::user()->id);

		$this->data = array(
			'pageTitle'	=> 'My Profile',
			'pageNote'	=> 'View Detail My Info',
			'info'		=> $info,
			'profile'  => $profile
		);
		$this->data['plans'] = Sponsorshipplan::all();
		$this->data['cards'] = Cards::where('entry_by',\Auth::user()->id)->get();
		$this->data['profile']['Expertization'] = explode(',',$this->data['profile']['Expertization']);
		$this->data['expertizes'] = \DB::table('tb_expertization')->lists('Expertize','Expertize');
		return view('user.profile',$this->data);
	}
	
	public function postSaveprofile( Request $request)
	{
		if(!\Auth::check()) return Redirect::to('user/login');
		$rules = array(
			'first_name'=>'required|alpha_num|min:2',
			'last_name'=>'required|alpha_num|min:2',
		);
			
		if($request->input('email') != \Session::get('eid'))
		{
			$rules['email'] = 'required|email|unique:tb_users';
		}
		if($request->input('City')!=''){
			$rules['City'] = 'Alpha';
		}

		$validator = Validator::make($request->all(), $rules);

		if ($validator->passes()) {
			
			
			if(!is_null(Input::file('avatar')))
			{
				$file = $request->file('avatar'); 
				$destinationPath = './uploads/users/';
				$filename = $file->getClientOriginalName();
				$extension = $file->getClientOriginalExtension(); //if you need extension of the file
				 $newfilename = \Session::get('uid').'.'.$extension;
				$uploadSuccess = $request->file('avatar')->move($destinationPath, $newfilename);				 
				if( $uploadSuccess ) {
				    $data['avatar'] = $newfilename; 
				} 
				
			}		
			
			$user = User::find(\Session::get('uid'));
			$user->first_name 	= $request->input('first_name');
			$user->last_name 	= $request->input('last_name');
			$user->email 		= $request->input('email');
			$user->contactNo	= $request->input('contactNo');
			$user->City =  $request->input('City');
			if(isset( $data['avatar']))  $user->avatar  = $newfilename; 			
			$user->save();

			return Redirect::to('user/profile')->with('messagetext','Profile has been saved!')->with('msgstatus','success');
		} else {
			return Redirect::to('user/profile')->with('messagetext','The following errors occurred')->with('msgstatus','error')
			->withErrors($validator)->withInput();
		}	
	
	}
	
	public function postSavepassword( Request $request)
	{
		$rules = array(
			'password'=>'required|between:6,12',
			'password_confirmation'=>'required|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$user = User::find(\Session::get('uid'));
			$user->password = \Hash::make($request->input('password'));
			$user->save();

			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	
	}

	public function postSavepatientinfo( Request $request)
	{
		$rules = array(
			'BirthDate'=>'required',
			'BloodGroup'=>'required'
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$patient = Patient::firstOrNew(['UserID' => \Session::get('uid')]);
			$patient->UserID=  \Session::get('uid');
			$patient->BirthDate = $request->input('BirthDate');
			$patient->BloodGroup = $request->input('BloodGroup');
			$patient->save();

			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Data has been saved!'));
		} else {
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}

	}

	public function postSavedoctorinfo( Request $request)
	{
		$rules = array(
			'Expertization'=>'required',
			'Degree'=>'required|min:2',
			'Experience' => 'required'
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			$doctor = Doctor::firstOrNew(['UserID' => \Session::get('uid')]);
			$doctor->UserID=  \Session::get('uid');

			$doctor->Degree = $request->input('Degree');
			$doctor->Experience = $request->input('Experience');
			$doctor->timezone = $request->input('timezone');
			$doctor->Fee= $request->input('Fee');
			foreach($request['Expertization'] as $e)
			{
				$exp= Expertize::firstOrCreate(['Expertize' => $e]);
				$exp->update(['Expertize' => $e,'display_name' => $e]);

			}
			$expertization = implode(',',$request['Expertization']);
			$doctor->Expertization = $expertization;
			$doctor->save();
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('success','Data has been saved!'));
		} else {
			return Redirect::to('user/profile')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}

	}

	public function getReminder()
	{

		return view('user.remind');
	}	

	public function postRequest( Request $request)
	{

		$rules = array(
			'credit_email'=>'required|email'
		);	
		
		$validator = Validator::make(Input::all(), $rules);
		if ($validator->passes()) {	
	
			$user =  User::where('email','=',$request->input('credit_email'));
			if($user->count() >=1)
			{
				$user = $user->get();
				$user = $user[0];
				$data = array('token'=>$request->input('_token'));	
				$to = $request->input('credit_email');
//				$subject = "[ " .CNF_APPNAME." ] REQUEST PASSWORD RESET ";
//				$message = view('user.emails.auth.reminder', $data);
//				$headers  = 'MIME-Version: 1.0' . "\r\n";
//				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//				$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
//					mail($to, $subject, $message, $headers);

				Mail::send('user.emails.auth.reminder', $data, function ($m) use ($to)  {
					$m->to($to)->subject("[ " .CNF_APPNAME." ] REQUEST PASSWORD RESET ");
				});
				
				$affectedRows = User::where('email', '=',$user->email)
								->update(array('reminder' => $request->input('_token')));
								
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('success','Please check your email'));	
				
			} else {
				return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find email address'));
			}

		}  else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	 
	}	
	
	public function getReset( $token = '')
	{
		if(\Auth::check()) return Redirect::to('dashboard');

		$user = User::where('reminder','=',$token);
		if($user->count() >=1)
		{
			$data = array('verCode'=>$token);
			return view('user.remind',$data);	
		} else {
			return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Cant find your reset code'));
		}
		
	}	
	
	public function postDoreset( Request $request , $token = '')
	{
		$rules = array(
			'password'=>'required|alpha_num|between:6,12|confirmed',
			'password_confirmation'=>'required|alpha_num|between:6,12'
			);		
		$validator = Validator::make($request->all(), $rules);
		if ($validator->passes()) {
			
			$user =  User::where('reminder','=',$token);
			if($user->count() >=1)
			{
				$data = $user->get();
				$user = User::find($data[0]->id);
				$user->reminder = '';
				$user->password = \Hash::make($request->input('password'));
				$user->save();			
			}

			return Redirect::to('user/login')->with('message',\SiteHelpers::alert('success','Password has been saved!'));
		} else {
			return Redirect::to('user/reset/'.$token)->with('message', \SiteHelpers::alert('error','The following errors occurred')
			)->withErrors($validator)->withInput();
		}	
	
	}	

	public function getLogout() {
		\Auth::logout();
		\Session::flush();
		return Redirect::to('');
	}

	function getSocialize( $social )
	{
		return Socialize::with($social)->redirect();
	}

	function getAutosocial( $social )
	{
		$user = Socialize::with($social)->user();
		$user =  User::where('email',$user->email)->first();
		return self::autoSignin($user);		
	}


	function autoSignin($user)
	{

		if(is_null($user)){
		  return Redirect::to('user/login')
				->with('message', \SiteHelpers::alert('error','You have not registered yet '))
				->withInput();
		} else{

		    Auth::login($user);
			if(Auth::check())
			{
				$row = User::find(\Auth::user()->id); 

				if($row->active =='0')
				{
					// inactive 
					Auth::logout();
					return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is not active'));

				} else if($row->active=='2')
				{
					// BLocked users
					Auth::logout();
					return Redirect::to('user/login')->with('message', \SiteHelpers::alert('error','Your Account is BLocked'));
				} else {
					if($row->group_id=='3'){
						\Session::put('ref_id',Doctor::where('UserID',$row->id)->pluck('DoctorID'));
						if($row->payment_method=='0')
						{
							$trial= date('Y-m-d H:i:s', strtotime('+'.CNF_TRIAL.' day',strtotime($row->created_at)));
							$trial = strtotime($trial);
							$now= strtotime(date('Y-m-d H:i:s'));
							if($trial<$now) {
								return redirect('user/subscribe')->with('message', \SiteHelpers::alert('error', 'Your trial or subscription expired.'));
							}
							if(Cookie::get('DoctorID')){
								return Redirect::to('appointment/book')->with('messagetext','You are in trail mode.')->with('msgstatus','success');
							}
							if(Doctor::where('UserID',$row->id)->count()==0)
							{
								return redirect('user/profile')->with('messagetext','You are in trail mode. <br/> Please Create Your Doctor Profile')->with('msgstatus','success');
							}
							return redirect('dashboard')->with('messagetext','You are in trail mode.')->with('msgstatus','success');
						}
					}
					if($row->group_id=='5'){
						//\Session::put('ref_id',Patient::where('UserID',$row->id)->pluck('PatientID'));
					}

					if(Cookie::get('DoctorID')){
						return Redirect::to('appointment/book');
					}
					\DB::table('tb_users')->where('id', '=',$row->id )->update(array('last_login' => date("Y-m-d H:i:s")));
					Session::put('uid', $row->id);
					Session::put('gid', $row->group_id);
					Session::put('eid', $row->group_email);
					Session::put('ll', $row->last_login);
					Session::put('fid', $row->first_name.' '. $row->last_name);
					if($row->group_id==3 && Doctor::where('UserID',$row->id)->count()==0)
					{
						return redirect('user/profile')->with('messagetext','You are in trail mode. <br/> Please Create Your Doctor Profile')->with('msgstatus','success');
					}
					if(CNF_FRONT =='false') :
						return Redirect::to('dashboard');						
					else :
						return Redirect::to('');
					endif;					
					
										
				}
				
				
			}
		}

	}

	public function postPaypal()
	{

    	$listener = new IpnListener();

		$listener->use_sandbox=true;
		try {
			$verified = $listener->processIpn();
		} catch (Exception $e) {
			return Log::error($e->getMessage());
		}

		if ($verified) {
				$data = $_POST;
				$user_id = json_decode($data['custom'])->user_id;
				$plan=\DB::table('tb_sub_plans')->where('amount','=',$data['mc_gross'])->first();
				$duration= '+'.$plan->duration.' Month';
				$expires= date('Y-m-d H:i:s', strtotime($duration));
			$payment = array(
				'transaction_id' => $data['txn_id'],
				'user_id'      => $user_id,
				'paypal_id'    => $data['subscr_id'],
				'paypal_plan' => $plan->id,
				'expires'      => $expires
			);
				$user = Payment::firstOrCreate(['user_id' => $payment['user_id']]);
			$user->update($payment);
				User::where('id', '=', $user_id)->update(['payment_method' => 'paypal','active' => 1]);
			}
		else
		{
			Log::error('Transaction not verified');
		}

	}

	public function getConnect(Request $request)
	{
		if($request->get('code')) {
			$code = $request->get('code');

			$token_request_body = array(
				'grant_type' => 'authorization_code',
				'client_id' => CNF_STRIPE_CLIENT_ID,
				'client_secret' => CNF_STRIPE_API_KEY,
				'code' => $code,
			);
			$client = new  \GuzzleHttp\Client();
			$postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';

			$response = $client->post('https://connect.stripe.com/oauth/token', [
				'headers' => ['Accept' => 'application/json'],
				$postKey => $token_request_body,
			]);

			$response = json_decode($response->getBody());
			print_r($response);

			if(isset($response->stripe_user_id)){
				\DB::table('tb_stripeuser')->insert(['userid' => \Session::get('uid'),'stripe_userid' => $response->stripe_user_id]);
				return redirect('/user/profile')->with('messagetext','You are connected to stripe successfully')->with('msgstatus','success');
			}
			return redirect('/user/profile')->with('messagetext','There were some problem.Try later')->with('msgstatus','info');
		}
		else{
			return redirect('/user/profile')->with('messagetext','There were some problem.Try later')->with('msgstatus','info');
		}

	}

	public function getCancel(Request $request)
	{
		if($request->ajax()==true)
		{
			try{
				$cust= Auth::user()->stripe_id;
				$subscription= Auth::user()->stripe_subscription;
				$customer = Customer::retrieve($cust);
				$subscribe = $customer->subscriptions->retrieve($subscription);
				$subscribe->cancel();
				Auth::user()->payment_method=0;
				Auth::user()->save();
				return 'true';
			}
			catch (InvalidRequest $e){
				return 'false';
			}
		}
		else{
			return "Oops, You can't access this page.";
		}
	}




}