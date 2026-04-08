<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStudent extends Model
{
    protected $table = 'master_students';

    protected $fillable = [
        'FullName',
        'NIS',
    ];
}
