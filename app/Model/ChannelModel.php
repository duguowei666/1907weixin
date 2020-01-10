<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ChannelModel extends Model
{
    protected $table = 'channel';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = [];
}
