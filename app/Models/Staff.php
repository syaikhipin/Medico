<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class staff extends Sximo  {
	
	protected $table = 'tb_staff';
	protected $primaryKey = 'StaffID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_staff.* FROM tb_staff  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_staff.StaffID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
