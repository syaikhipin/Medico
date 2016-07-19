<?php namespace App\Models;

use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class doctor extends Sximo  {
	
	protected $table = 'tb_doctor';
	protected $primaryKey = 'DoctorID';
	public $timestamps= false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_doctor.* FROM tb_doctor  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_doctor.DoctorID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public static function getDetail($id){
		return User::find($id);
	}
	

}
