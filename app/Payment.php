<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table='tb_payments';
    protected $fillable= ['transaction_id','user_id','paypal_id','paypal_plan','expires'];

}
