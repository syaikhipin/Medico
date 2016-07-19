<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class cards extends Sximo  {
	
	protected $table = 'tb_stripe_cards';
	protected $primaryKey = 'id';
	public $timestamps=false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return "  SELECT tb_stripe_cards.* FROM tb_stripe_cards  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE tb_stripe_cards.id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
