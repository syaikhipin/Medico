<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Mailbox;
use App\Library\MailboxHelpers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator, Input, Redirect ;

class MailboxController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'mailbox';
	static $per_page	= '10';
	
	public function __construct() 
	{
		parent::__construct();
		$this->model = new Mailbox();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'			=> 	$this->info['title'],
			'pageNote'			=>  $this->info['note'],
			'pageModule'		=> 'mailbox',
			'pageUrl'			=>  url('mailbox'),
			'return' 			=> 	self::returnUrl()	
		);
		
			
				
	} 
	
	public function getIndex()
	{
		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')->with('messagetext',\Lang::get('core.note_restric'))->with('msgstatus','error');
				
		$this->data['access']		= $this->access;	
		return view('mailbox.index',$this->data);
	}	

	public function postData( Request $request)
	{ 
		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : $this->info['setting']['orderby']); 
		$order = (!is_null($request->input('order')) ? $request->input('order') : $this->info['setting']['ordertype']);
		// End Filter sort and order for query 

		// Filter Search for query		
		$filter = (!is_null($request->input('search')) ? $this->buildSearch() : " AND Status ='inbox' ");
		

		/* Title */
		$search = explode("|",$request->input('search') );
		$this->data['mail_title'] = 'Inbox';
		foreach($search as $t)
		{
			$keys = explode(":",$t);
			$this->data['mail_title'] =  in_array('Status',$keys) ? ucwords($keys['2']) : 'Inbox';
		}	

		if($this->data['mail_title'] == 'Draft')
		{
			$filter .= " AND SenderID = '".\Session::get('uid')."'  ";

		} else if($this->data['mail_title'] == 'Sent' ) {

			$filter .= " AND SenderID = '".\Session::get('uid')."'  ";

		} else if($this->data['mail_title'] == 'Trash' ) {

			$filter .= " AND ReceiverID = '".\Session::get('uid')."'  ";	
		} else {

			$filter .= " AND ReceiverID = '".\Session::get('uid')."'  ";	
		}
				
		$this->data['totalInbox'] = \DB::table('mailbox')->where('ReceiverID',\Session::get('uid'))->where('IsView','0')->where('Status','inbox')->get();
		$this->data['totalDraft'] = \DB::table('mailbox')->where('SenderID',\Session::get('uid'))->where('Status','draft')->get();


		
		$page = $request->input('page', 1);
		$params = array(
			'page'		=> $page ,
			'limit'		=> (!is_null($request->input('rows')) ? filter_var($request->input('rows'),FILTER_VALIDATE_INT) : $this->info['setting']['perpage'] ) ,
			'sort'		=> $sort ,
			'order'		=> $order,
			'params'	=> $filter,
			'global'	=> 1
		);
		// Get Query 
		$results = $this->model->getRows( $params );

		$this->data['mail_total'] =  $results['total'] ;
		// Build pagination setting
		$page = $page >= 1 && filter_var($page, FILTER_VALIDATE_INT) !== false ? $page : 1;	
		$pagination = new Paginator($results['rows'], $results['total'], $params['limit']);	
		$pagination->setPath('mailbox/data');

		
			
		
		$this->data['param']		= $params;
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
		$this->data['setting'] 		= $this->info['setting'];
		
		// Master detail link if any 
		$this->data['subgrid']	= (isset($this->info['config']['subgrid']) ? $this->info['config']['subgrid'] : array()); 
		// Render into template
		return view('mailbox.table',$this->data);
		

	}

	function getForward(Request $request, $id  ){

		$query = \DB::table('mailbox')->where('id',$id)->get();
		if(count($query) >=1)
		{
			$this->data['row'] = $query[0];
			return view('mailbox.forward',$this->data);

		} else {
			return ' Ops Cant Find The Email ';
		}

	}

	function postForward(Request $request, $id )
	{
		$query = \DB::table('mailbox')->where('id',$id)->get();
		if(count($query) >=1)
		{
			$row = $query[0];
			$data = array(
				'SenderId' 		=> \Session::get('uid'),
				'ReceiverId'	=> $request->input('ReceiverID'),
				'Subject'		=> 'Forward : '.$row->Subject ,
				'Message'		=> $row->Message ,
				'SentDate'		=> date('Y-m-d H:i:s'),
				'CreatedDate'	=> $row->CreatedDate,
				'IsView'		=> 0 ,
				'Status'		=> 'inbox'
			);
			\DB::table('mailbox')->insert($data);

			$data['Status']	= 'sent';
			\DB::table('mailbox')->insert($data);

			return response()->json(array(
				'status'=>'success',
				'message'=> 'Email Has Been Sent !'
			));

		} else {
			return ' Ops Cant Find The Email ';
		}
	}	

	function getUpdate(Request $request, $id = null)
	{

		$this->data['act'] = ( $request->input('act') !='' ? $request->input('act') : 'sent');
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
			$this->data['row'] 		=  $row;
		} else {
			$this->data['row'] 		= $this->model->getColumnTable('mailbox'); 
		}
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		=  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		$this->data['receivers'] = $this->getReceivers();
		return view('mailbox.form',$this->data);
	}	

	public function getShow( Request $request, $id = null)
	{
	
		if($this->access['is_detail'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', Lang::get('core.note_restric'))->with('msgstatus','error');
					
		$row = $this->model->getRow($id);
		if($row)
		{
			$this->data['row'] =  $row;
		} else {
			$this->data['row'] = $this->model->getColumnTable('mailbox'); 
		}
		\DB::table('mailbox')->where('id',$id)->update(array('IsView'=>'1'));
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		$this->data['setting'] 		= $this->info['setting'];
		$this->data['fields'] 		= \AjaxHelpers::fieldLang($this->info['config']['forms']);
		$this->data['act'] = ( $request->input('act') !='' ? strtolower($request->input('act')) : 'sent');
		return view('mailbox.view',$this->data);	
	}	


	function postTrash( Request $request)
	{
		$ids = $request->input('ids');
		$act = $request->input('act');

		if($act =='Read')
		{

			\DB::table('mailbox')->whereIn('Id',$ids)->update(array('IsView'=>'1'));
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('Email Has been mark as Read')
			));	
		} else if($act =='Trash'){

			\DB::table('mailbox')->whereIn('Id',$ids)->delete();
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('Email Has been remove permanently')
			));	

		} else {
			\DB::table('mailbox')->whereIn('Id',$ids)->update(array('Status'=>'trash'));
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('Email Has been move to Trash')
			));				


		}
		
	}

	function postCopy( Request $request)
	{
		
	    foreach(\DB::select("SHOW COLUMNS FROM mailbox ") as $column)
        {
			if( $column->Field != 'Id')
				$columns[] = $column->Field;
        }
		$toCopy = implode(",",$request->input('ids'));
		
				
		$sql = "INSERT INTO mailbox (".implode(",", $columns).") ";
		$sql .= " SELECT ".implode(",", $columns)." FROM mailbox WHERE Id IN (".$toCopy.")";
		\DB::insert($sql);
		return response()->json(array(
			'status'=>'success',
			'message'=> \Lang::get('core.note_success')
		));	
	}		

	function postSave( Request $request, $id =0)
	{
		$act = $request->input('act');
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('mailbox');

			/* insert to sent mail */
			$data['IsView'] = 0;
			$data['Status'] = ($act  =='draft' ? 'draft' : 'sent');
			$data['CreatedDate'] = date('Y-m-d H:i:s');
			$data['SentDate'] = date('Y-m-d H:i:s');		
			$id = $this->model->insertRow($data , $request->input('Id'));

			/* End insert as sent mail */

			/* Insert row to receiver as inbox mail */
			if($act  !='draft')
			{
				$receiver = explode(',', $request->input('ReceiverID'));
				foreach ($receiver as $to) {
					$data['ReceiverID'] = $to;
					$data['Status'] = 'inbox';
					$this->model->insertRow($data , $request->input('Id'));
				

					/* add notificatio to spesific users */
			        $notif = array(
			            'url'   => url('mailbox'),
			            'userid'    => $to,
			            'title'     => 'You Have Got New Inbox ',
			            'note'      => 'You Have Got New Inbox , Please check your mailbox',
						'entry_by'	=> $to,
			        );
			        \SximoHelpers::storeNote($notif);
					/* End notification to spesific users */

					/* Send email to users */
					$users = \DB::table('tb_users')->where('id',$to)->get();
					if(count($users)>=1)
					{
						$row = $users[0];
						$to = $row->email;
						$data['row'] = $row;
//						$subject = "[ " .CNF_APPNAME." ] You Have Got New Inbox ";
//						$message = view('mailbox.email', $data);
//						$headers  = 'MIME-Version: 1.0' . "\r\n";
//						$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//						$headers .= 'From: '.CNF_APPNAME.' <'.CNF_EMAIL.'>' . "\r\n";
//							mail($to, $subject, $message, $headers);

						Mail::send('mailbox.email', $data, function ($m) use ($to)  {
							$m->to($to)->subject("[ " .CNF_APPNAME." ] You Have Got New Inbox ");
						});

					}


					/* End send email notification to users */
				}	


			}
		
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('core.note_success')
				));	

		} else {

			$message = $this->validateListError(  $validator->getMessageBag()->toArray() );
			return response()->json(array(
				'message'	=> $message,
				'status'	=> 'error'
			));	
		}	
	
	}	

	public function postDelete( Request $request)
	{

		if($this->access['is_remove'] ==0) {   
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_restric')
			));
			die;

		}		
		// delete multipe rows 
		if(count($request->input('ids')) >=1)
		{
			$this->model->destroy($request->input('ids'));
			
			return response()->json(array(
				'status'=>'success',
				'message'=> \Lang::get('core.note_success_delete')
			));
		} else {
			return response()->json(array(
				'status'=>'error',
				'message'=> \Lang::get('core.note_error')
			));

		} 		

	}	

	public static function Users($id) {
		$Q = \DB::select(" SELECT * FROM tb_users WHERE id = '".$id."' ");
		
		if(count($Q) >= 1 )
		{
			$row = $Q[0];
			return $row->first_name .' '.$row->last_name;
		} else {
			return '';
		}
	}

	function getReceivers(){
		if(Auth::user()->group_id==1){
			$receivers = User::where('id','!=',\Auth::user()->id)->get();
		}
		elseif(Auth::user()->group_id==2){
			$receivers = User::where('id','!=',\Auth::user()->id)->where('group_id',1)->orWhere('group_id',3)->get();
		}
		elseif(Auth::user()->group_id==3){
			$doctors = \DB::table('tb_doctor_patient')->where('FamilyMemberID',\Auth::user()->id)->lists('DoctorUserID');
			$members = \DB::table('tb_doctor_patient')->where('DoctorUserID',\Auth::user()->id)->lists('FamilyMemberID');
			$receivers = User::where('group_id',1)->where('id','!=',\Auth::user()->id)->orWhereIn('id',$doctors)->orWhereIn('id',$members)->get();
		}
		else{
			$doctors = \DB::table('tb_doctor_patient')->where('FamilyMemberID',\Auth::user()->id)->lists('DoctorUserID');
			$receivers = User::where('group_id',1)->orWhereIn('id',$doctors)->get();
		}
		return $receivers;

	}

}