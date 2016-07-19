<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class medicine extends Sximo  {
	
	protected $table = 'tb_Medicine';
	protected $primaryKey = 'MedicineID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_Medicine.* FROM tb_Medicine  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_Medicine.MedicineID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
