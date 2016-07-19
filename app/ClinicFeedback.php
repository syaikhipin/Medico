<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClinicFeedback extends Model
{
    protected $table='tb_clinic_feedback';
    protected $fillable= ['ToClinicID','FromUserID','Feedback'];

}