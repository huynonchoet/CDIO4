<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    protected $table = 'comments';
    protected $fillable = ['id_user','user_name','user_avatar','id_blog','content','level'];
    public $timestamp = false;
}
