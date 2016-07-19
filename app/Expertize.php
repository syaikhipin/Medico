<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expertize extends Model
{
    protected $table='tb_expertization';
    public $timestamps = false;
    protected $fillable= ['Expertize','display_name'];

}
