<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterAdmin extends Model
{
    protected $table = 'master_admins';

    protected $fillable = [
        'FullName',
        'NUPTK',
    ];
}
