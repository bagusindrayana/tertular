<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PasienStatus extends Model
{
    use SoftDeletes;
    protected $guarded = [];
}
