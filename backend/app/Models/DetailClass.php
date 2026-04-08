<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailClass extends Model
{
    protected $table = 'detail_classes';

    protected $fillable = [
        'Slot',
        'StudentId',
        'ClassId',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'StudentId');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'ClassId');
    }
}
