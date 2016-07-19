<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class prescriptionmedicine extends Sximo  {
	
	protected $table = 'tb_prescription_medicine';
	protected $primaryKey = 'PresMedicineID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_prescription_medicine.* FROM tb_prescription_medicine  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_prescription_medicine.PresMedicineID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
