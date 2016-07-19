<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class scheduledetail extends Sximo  {
	
	protected $table = 'tb_Schedule_Detail';
	protected $primaryKey = 'ScheduleDetailID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_Schedule_Detail.* FROM tb_Schedule_Detail  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_Schedule_Detail.ScheduleDetailID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
