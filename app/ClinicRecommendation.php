<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicRecommendation extends Model
{
    protected $table='tb_clinic_recommendation';
    protected $fillable= ['UserID','ClinicID'];
    public  $timestamps= false;

}

