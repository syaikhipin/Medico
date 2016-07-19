<?php namespace App\Http\Controllers;

use App\Http\Controllers\controller;
use App\Models\Cards;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Stripe\Customer;
use Stripe\Stripe;
use Validator, Input, Redirect ;


class CardsController extends Controller {

	protected $layout = "layouts.main";
	protected $data = array();	
	public $module = 'cards';
	static $per_page	= '10';

	public function __construct()
	{
		parent::__construct();
		$this->beforeFilter('csrf', array('on'=>'post'));
		$this->model = new Cards();
		
		$this->info = $this->model->makeInfo( $this->module);
		$this->access = $this->model->validAccess($this->info['id']);
	
		$this->data = array(
			'pageTitle'	=> 	$this->info['title'],
			'pageNote'	=>  $this->info['note'],
			'pageModule'=> 'cards',
			'pageUrl'			=>  url('cards'),
			'return'	=> self::returnUrl()
			
		);

		Stripe::setApiKey(CNF_STRIPE_API_KEY);
		
	}

	public function getIndex( Request $request )
	{

		if($this->access['is_view'] ==0) 
			return Redirect::to('dashboard')
				->with('messagetext', \Lang::get('core.note_restric'))->with('msgstatus','error');

		$sort = (!is_null($request->input('sort')) ? $request->input('sort') : 'id'); 
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
		$pagination->setPath('cards');
		
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
		return view('cards.index',$this->data);
	}	



	function getUpdate(Request $request, $id = null)
	{
		if($request->has('continue')){
			$this->data['continue'] = $request->continue;
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
			$this->data['row'] = $this->model->getColumnTable('tb_stripe_cards'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);

		
		$this->data['id'] = $id;
		return view('cards.form',$this->data);
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
			$this->data['row'] = $this->model->getColumnTable('tb_stripe_cards'); 
		}
		$this->data['fields'] =  \AjaxHelpers::fieldLang($this->info['config']['forms']);
		
		$this->data['id'] = $id;
		$this->data['access']		= $this->access;
		return view('cards.view',$this->data);	
	}	

	function postSave( Request $request)
	{
		
		$rules = $this->validateForm();
		$validator = Validator::make($request->all(), $rules);	
		if ($validator->passes()) {
			$data = $this->validatePost('tb_cards');
			if(\Auth::user()->stripe_id!="")
			$customer = \Stripe\Customer::retrieve(\Auth::user()->stripe_id);
			else {
				$customer = Customer::create(array(
					'email' => \Auth::user()->email
				));
				\Auth::user()->stripe_id = $customer->id;
				\Auth::user()->save();
			}
			$card= $customer->sources->create(array("source" => $request->input('stripeToken')));
			if($request->input('isdefault')){
				$customer->default_source = $card->id;
				$customer->save();
			}


			$dbcard= new Cards();
			$dbcard->cust_id= $customer->id;
			$dbcard->card_id=$card->id;
			$dbcard->brand= $card->brand;
			$dbcard->name= $request->input('name');
			$dbcard->isdefault=$request->input('isdefault');
			$dbcard->last4= "XXXX-XXXX-XXXX-".substr($request->input('ccn'),strlen($request->input('ccn'))-4,strlen($request->input('ccn')));
			$dbcard->entry_by=\Session::get('uid');
			$id= $dbcard->save();


			// Insert logs into database
			if($request->input('id') =='')
			{
				\SiteHelpers::auditTrail( $request , 'New Data with ID '.$id.' Has been Inserted !');
			} else {
				\SiteHelpers::auditTrail($request ,'Data with ID '.$id.' Has been Updated !');
			}
			if($request->has('continue')){
				return Redirect::to( $request->continue )->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			}
			return Redirect::to('/cards')->with('messagetext',\Lang::get('core.note_success'))->with('msgstatus','success');
			
		} else {

			return Redirect::to('cards')->with('messagetext',\Lang::get('core.note_error'))->with('msgstatus','error')
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

            $cards= $request->input('ids');
            foreach($cards as $cardid){
                $card= Cards::find($cardid);
                if(!$card->isdefault) {
                    $customer = Customer::retrieve(\Auth::user()->stripe_id);
                    $customer->sources->retrieve($card->card_id)->delete();
                    $card->delete();
                }
            }
			\SiteHelpers::auditTrail( $request , "ID : ".implode(",",$request->input('ids'))."  , Has Been Removed Successfull");
			// redirect
			return Redirect::to('cards')
        		->with('messagetext', \Lang::get('core.note_success_delete'))->with('msgstatus','success'); 
	
		} else {
			return Redirect::to('cards')
        		->with('messagetext','No Item Deleted')->with('msgstatus','error');				
		}

	}

    public function getDefault(Request $request,$id)
    {
        $customer = Customer::retrieve(\Auth::user()->stripe_id);
        Cards::where('entry_by', '=', \Auth::user()->id)->where('isdefault', '=', '1')->update(['isdefault' => 0]);
        $card = Cards::find($id);
        $card->isdefault = 1;

        $customer->default_source = $card->card_id;
        $customer->save();
        $card->save();
        return Redirect::to('/cards')->with('messagetext','Card Updated Successfully')->with('msgstatus','success');

    }


}