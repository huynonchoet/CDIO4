<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class rates extends Model
{
    protected $table= 'rates';
    protected $fillable = [
        'id_user','id_blog','star'
    ];
    public $timestamp = false;
}
