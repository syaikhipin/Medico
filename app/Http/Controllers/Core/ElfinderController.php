<?php namespace App\Http\Controllers\Core;

use App\Http\Controllers\controller;
use App\Models\Core\Groups;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Validator, Input, Redirect ; 

class ElfinderController extends Controller {

	public function __construct()
	{

	}	

	public function getIndex( Request $request )
	{
		$data = array();
		//return public_path().'/uploads/userfiles/';
		if(!is_dir(public_path().'/media/userfiles/')) mkdir(public_path().'/media/userfiles/',0777);
		$groupID = \Session::get('gid');
		if($groupID ==1 or $groupID ==2 ) 
		{
			$data['folder'] = 'media/';
		} else {
			$folder = \Session::get('uid');
			if(!is_dir('./media/userfiles/'.$folder )) mkdir('./media/userfiles/'.$folder ,0777);
			$data['folder'] = 'media/userfiles/'.$folder ;
			
		}
		if(!is_null($request->get('cmd')))
		{
			return view('core.elfinder.connector',$data);

		} else {
			return view('core.elfinder.filemanager',$data);
		}
	
	}
	
	static function display()
	{
		return view('core.elfinder.filemanager');	
	} 
	
	
}