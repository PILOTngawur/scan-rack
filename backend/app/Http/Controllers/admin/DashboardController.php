<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\DetailClass;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDevices = DetailClass::query()
            ->whereNotNull('StudentId')
            ->distinct('StudentId')
            ->count('StudentId');

        $racks = ClassModel::withCount([
            'detailClasses as used_slots' => fn($q) => $q->whereNotNull('StudentId'),
        ])->get();

        return view('admin.dashboard.index', compact('totalDevices', 'racks'));
    }
}
