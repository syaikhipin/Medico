<?php namespace App\Models;

use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Sponsorship extends Sximo  {

    protected $table = 'tb_sponsorship';
    protected $primaryKey = 'id';

    protected  $fillable  = ['DoctorID','ClinicID','Plan','Enddate','Charge','entry_by'];
    public $timestamps= false;

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT tb_sponsorship.* FROM tb_sponsorship  ";
    }

    public static function queryWhere(  ){

        return "  WHERE tb_sponsorship.id IS NOT NULL ";
    }

    public static function queryGroup(){
        return "  ";
    }

}
