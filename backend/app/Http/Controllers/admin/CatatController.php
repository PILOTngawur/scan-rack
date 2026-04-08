<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\DetailClass;
use Illuminate\Http\Request;

class CatatController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());
        $selectedClassIds = collect($request->input('class_ids', []))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $classOptions = ClassModel::query()->orderBy('ClassName')->get();

        $recordsQuery = DetailClass::query()
            ->with(['student.class', 'class'])
            ->whereNotNull('StudentId')
            ->whereDate('updated_at', $selectedDate)
            ->when(
                ! empty($selectedClassIds),
                fn($q) => $q->whereIn('ClassId', $selectedClassIds)
            )
            ->orderBy('ClassId')
            ->orderBy('Slot');

        $records = $recordsQuery->get();
        $classGroups = $records
            ->groupBy(fn($record) => (string) ($record->ClassId ?? 0))
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'class_name' => $first?->class?->ClassName ?? 'Tanpa Kelas',
                    'rack_name' => $first?->class?->RackName ?? '-',
                    'total' => $items->count(),
                    'records' => $items->sortBy('Slot')->values(),
                ];
            })
            ->values();

        $totalStudentsCollected = (clone $recordsQuery)->count();
        $totalPhonesCollected = $totalStudentsCollected;

        $classSummary = DetailClass::query()
            ->selectRaw('ClassId, COUNT(*) as students_count, MAX(updated_at) as last_put_at')
            ->whereNotNull('StudentId')
            ->whereDate('updated_at', $selectedDate)
            ->when(
                ! empty($selectedClassIds),
                fn($q) => $q->whereIn('ClassId', $selectedClassIds)
            )
            ->with('class')
            ->groupBy('ClassId')
            ->orderBy('ClassId')
            ->get();

        return view('admin.catat.index', compact(
            'selectedDate',
            'selectedClassIds',
            'classOptions',
            'records',
            'classGroups',
            'totalStudentsCollected',
            'totalPhonesCollected',
            'classSummary',
        ));
    }

    public function print(Request $request)
    {
        $selectedDate = $request->input('date', now()->toDateString());
        $selectedClassIds = collect($request->input('class_ids', []))
            ->filter()
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $classOptions = ClassModel::query()->orderBy('ClassName')->get();

        $recordsQuery = DetailClass::query()
            ->with(['student.class', 'class'])
            ->whereNotNull('StudentId')
            ->whereDate('updated_at', $selectedDate)
            ->when(
                ! empty($selectedClassIds),
                fn($q) => $q->whereIn('ClassId', $selectedClassIds)
            )
            ->orderBy('ClassId')
            ->orderBy('Slot');

        $records = $recordsQuery->get();
        $classGroups = $records
            ->groupBy(fn($record) => (string) ($record->ClassId ?? 0))
            ->map(function ($items) {
                $first = $items->first();

                return [
                    'class_name' => $first?->class?->ClassName ?? 'Tanpa Kelas',
                    'rack_name' => $first?->class?->RackName ?? '-',
                    'total' => $items->count(),
                    'records' => $items->sortBy('Slot')->values(),
                ];
            })
            ->values();

        $totalStudentsCollected = $records->count();
        $totalPhonesCollected = $totalStudentsCollected;

        return view('admin.catat.print', compact(
            'selectedDate',
            'selectedClassIds',
            'classOptions',
            'records',
            'classGroups',
            'totalStudentsCollected',
            'totalPhonesCollected',
        ));
    }
}
