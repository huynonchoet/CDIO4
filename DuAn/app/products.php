<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name','image','price','id_member','brand','category','sale','profile','detail'
    ];
    public $timestamp = false;
}
