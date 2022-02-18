<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class blogs extends Model
{
    protected $table = 'blogs';
    protected $fillable = [
        'title','image','description','content'
    ];
    public $timestamp = false;
}
