<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class prescription extends Sximo  {
	
	protected $table = 'tb_prescription';
	protected $primaryKey = 'PrescriptionID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_prescription.* FROM tb_prescription  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_prescription.PrescriptionID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
