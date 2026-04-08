<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\DetailClass;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RakController extends Controller
{
    public function index()
    {
        $racks = ClassModel::withCount([
            'detailClasses as used_slots' => fn($q) => $q->whereNotNull('StudentId'),
        ])->get();

        $name = ClassModel::select('ClassName')->distinct()->get();


        return view('admin.rak.index', compact('racks', 'name'));
    }

    public function create()
    {
        return view('admin.rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'RackName'  => 'required|string|max:255',
            'ClassName' => 'required|string|max:255',
            'SlotTotal' => 'required|integer|min:1|max:100',
        ], [
            'RackName.required'  => 'Nama rak wajib diisi.',
            'ClassName.required' => 'Kelas wajib dipilih.',
            'SlotTotal.required' => 'Total slot wajib diisi.',
            'SlotTotal.min'      => 'Total slot minimal 1.',
            'SlotTotal.max'      => 'Total slot maksimal 100.',
        ]);

        $qrCode = Str::uuid()->toString();

        $rack = ClassModel::create([
            'ClassName' => $request->ClassName,
            'RackName'  => $request->RackName,
            'SlotTotal' => $request->SlotTotal,
            'QrCode'    => $qrCode,
        ]);

        // Create slots
        for ($i = 1; $i <= $request->SlotTotal; $i++) {
            DetailClass::create([
                'Slot'      => $i,
                'ClassId'   => $rack->id,
                'StudentId' => null,
            ]);
        }

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil ditambahkan.');
    }

    public function show(ClassModel $rak)
    {
        $slots = DetailClass::where('ClassId', $rak->id)
            ->with('student')
            ->orderBy('Slot')
            ->get();

        $usedSlots  = $slots->whereNotNull('StudentId')->count();
        $emptySlots = $slots->whereNull('StudentId')->count();

        return view('admin.rak.show', compact('rak', 'slots', 'usedSlots', 'emptySlots'));
    }

    public function edit(ClassModel $rak)
    {
        return view('admin.rak.edit', compact('rak'));
    }

    public function update(Request $request, ClassModel $rak)
    {
        $request->validate([
            'RackName'  => 'required|string|max:255',
            'ClassName' => 'required|string|max:255',
            'SlotTotal' => 'required|integer|min:1|max:100',
        ]);

        $oldSlotTotal = $rak->SlotTotal;
        $newSlotTotal = (int) $request->SlotTotal;

        $rak->update([
            'ClassName' => $request->ClassName,
            'RackName'  => $request->RackName,
            'SlotTotal' => $newSlotTotal,
        ]);

        if ($newSlotTotal > $oldSlotTotal) {
            for ($i = $oldSlotTotal + 1; $i <= $newSlotTotal; $i++) {
                DetailClass::firstOrCreate(
                    ['ClassId' => $rak->id, 'Slot' => $i],
                    ['StudentId' => null]
                );
            }
        } elseif ($newSlotTotal < $oldSlotTotal) {
            DetailClass::where('ClassId', $rak->id)
                ->where('Slot', '>', $newSlotTotal)
                ->delete();
        }

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil diperbarui.');
    }

    public function destroy(ClassModel $rak)
    {
        $rak->detailClasses()->delete();
        $rak->delete();

        return redirect()->route('admin.rak.index')
            ->with('success', 'Rak berhasil dihapus.');
    }

    public function generateQr(ClassModel $rak)
    {
        $qrCode = Str::uuid()->toString();
        $rak->update(['QrCode' => $qrCode]);

        return response()->json(['qr_code' => $qrCode]);
    }
}
