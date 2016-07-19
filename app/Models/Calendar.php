<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class calendar extends Sximo  {
	
	protected $table = 'calendar';
	protected $primaryKey = 'id';
	public $timestamps = false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT calendar.* FROM calendar  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE calendar.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
