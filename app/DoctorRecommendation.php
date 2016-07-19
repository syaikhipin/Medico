<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorRecommendation extends Model
{
    protected $table='tb_doctor_recommendation';
    protected $fillable= ['UserID','DoctorID'];

}

