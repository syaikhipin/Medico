<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class clinicschedule extends Sximo  {
	
	protected $table = 'tb_clinic_schedule';
	protected $primaryKey = 'ScheduleID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_clinic_schedule.* FROM tb_clinic_schedule  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_clinic_schedule.ScheduleID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}

	public function ScheduleDetail(){
		return $this->hasMany('\App\Models\Scheduledetail','ScheduleID','ScheduleID')->get();
	}
	

}
