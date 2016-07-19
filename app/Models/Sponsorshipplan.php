<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class sponsorshipplan extends Sximo  {
	
	protected $table = 'tb_sponsorship_plans';
	protected $primaryKey = 'id';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_sponsorship_plans.* FROM tb_sponsorship_plans  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_sponsorship_plans.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
