<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['visitornum','duration','url'];

    public function visitors(){

        return $this->hasMany('App\Visitor');
    }
    public function feedback(){

        return $this->hasOne('App\Feedback');
    }
}
