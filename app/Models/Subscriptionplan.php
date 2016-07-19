<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class subscriptionplan extends Sximo  {
	
	protected $table = 'tb_sub_plans';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_sub_plans.* FROM tb_sub_plans  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_sub_plans.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
