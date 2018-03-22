<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = ['scroll','period','time'];

    public function address(){

        return $this->belongsTo('App\Address');
    }
}
