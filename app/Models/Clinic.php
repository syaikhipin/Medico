<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class clinic extends Sximo  {
	
	protected $table = 'tb_clinic';
	protected $primaryKey = 'ClinicID';
	public $timestamps = false;
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_clinic.* FROM tb_clinic  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_clinic.ClinicID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}



	

}
