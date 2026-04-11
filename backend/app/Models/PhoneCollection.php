<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneCollection extends Model
{
    protected $table = 'phone_collections';

    protected $fillable = [
        'CollectionDate',
        'StudentId',
        'ClassId',
        'PhoneCount',
        'Notes',
        'RecordedBy',
    ];

    protected $casts = [
        'CollectionDate' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'StudentId');
    }

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'ClassId');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'RecordedBy');
    }
}
