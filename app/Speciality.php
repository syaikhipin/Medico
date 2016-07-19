<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $table='tb_speciality';
    public $timestamps = false;
    protected $fillable= ['Speciality','display_name'];

}
