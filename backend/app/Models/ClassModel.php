<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'ClassName',
        'RackName',
        'RackSlot',
        'SlotTotal',
        'QrCode',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'ClassId');
    }

    public function detailClasses()
    {
        return $this->hasMany(DetailClass::class, 'ClassId');
    }

    public function usedSlotsCount(): int
    {
        return $this->detailClasses()->whereNotNull('StudentId')->count();
    }

    public function availableSlotsCount(): int
    {
        return ($this->SlotTotal ?? 0) - $this->usedSlotsCount();
    }
}
