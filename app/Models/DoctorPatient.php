<?php namespace App\Models;

use App\User;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class DoctorPatient extends Sximo  {

    protected $table = 'tb_doctor_patient';
    protected $primaryKey = 'id';

    protected $fillable = ['DoctorUserID','FamilyMemberID'] ;
    public $timestamps= false;

    public function __construct() {
        parent::__construct();

    }

    public static function querySelect(  ){

        return "  SELECT tb_doctor_patient.* FROM tb_doctor_patient  ";
    }

    public static function queryWhere(  ){

        return "  WHERE tb_doctor_patient.id IS NOT NULL ";
    }

    public static function queryGroup(){
        return "  ";
    }

    public static function getDetail($id){
        return User::find($id);
    }




}
