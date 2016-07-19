<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class report extends Sximo  {
	
	protected $table = 'tb_reports';
	protected $primaryKey = 'ReportID';

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_reports.* FROM tb_reports  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_reports.ReportID IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
