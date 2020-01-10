<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class XinWenModel extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'xinwen';
    public $timestamps = false;
    protected $guarded = [];
}
