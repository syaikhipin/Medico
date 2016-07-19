<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorFeedback extends Model
{
    protected $table='tb_doctor_feedback';
    protected $fillable= ['ToDoctorID','FromUserID','Feedback'];

}