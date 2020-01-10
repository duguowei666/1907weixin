<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CuserModel extends Model
{
    protected $table = 'c_user';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
}
