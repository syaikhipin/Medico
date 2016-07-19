<?php namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class mailbox extends Sximo  {
	
	protected $table = 'mailbox';
	protected $primaryKey = 'Id';
	public $timestamps = false;

	public function __construct() {
		parent::__construct();
		
	}

	public static function querySelect(  ){
		
		return " SELECT 
	mailbox.* ,
	CONCAT(first_name,' ',last_name) AS Sender

FROM mailbox
LEFT JOIN tb_users ON mailbox.SenderID = tb_users.`id`
  ";
	}	

	public static function queryWhere(  ){
		
		return "  WHERE mailbox.Id IS NOT NULL ";
	}
	
	public static function queryGroup(){
		return "  ";
	}
	

}
