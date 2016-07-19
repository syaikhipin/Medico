<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class appointment extends Sximo  {
	
	protected $table = 'tb_appointment';
	protected $primaryKey = 'AppointmentID';
	public $timestamps= false;
	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_appointment.* FROM tb_appointment  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_appointment.AppointmentID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
