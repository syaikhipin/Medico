<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class patient extends Sximo  {
	
	protected $table = 'tb_patient';
	protected $primaryKey = 'PatientID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_patient.* FROM tb_patient  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_patient.PatientID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
