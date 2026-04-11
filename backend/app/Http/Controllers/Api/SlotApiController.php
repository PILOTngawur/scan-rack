<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DetailClass;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SlotApiController extends Controller
{
    public function mySlot(Request $request): JsonResponse
    {
        $student = $this->resolveCurrentStudent($request);
        if (! $student) {
            return response()->json(['message' => 'User tidak valid.'], 401);
        }

        $slot = DetailClass::query()
            ->where('StudentId', $student->getKey())
            ->with('class')
            ->first();

        return response()->json([
            'status' => $slot ? 'checked_in' : 'not_checked_in',
            'data' => $slot,
        ]);
    }

    public function checkIn(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ClassId' => ['required', 'integer', 'exists:classes,id'],
            'Slot' => ['required', 'integer', 'min:1'],
        ]);

        $student = $this->resolveCurrentStudent($request);
        if (! $student) {
            return response()->json(['message' => 'User tidak valid.'], 401);
        }

        if ((int) $student->ClassId !== (int) $validated['ClassId']) {
            return response()->json([
                'message' => 'Tidak dapat check-in ke rak kelas lain.',
            ], 403);
        }

        $slot = DetailClass::query()
            ->where('ClassId', $validated['ClassId'])
            ->where('Slot', $validated['Slot'])
            ->first();

        if (! $slot) {
            return response()->json(['message' => 'Slot tidak ditemukan.'], 404);
        }

        if ($slot->StudentId !== null) {
            return response()->json(['message' => 'Slot sudah terisi.'], 422);
        }

        $alreadyHasSlot = DetailClass::query()->where('StudentId', $student->getKey())->exists();
        if ($alreadyHasSlot) {
            return response()->json(['message' => 'Siswa sudah check-in pada slot lain.'], 422);
        }

        $slot->update([
            'StudentId' => $student->getKey(),
        ]);

        return response()->json([
            'message' => 'Check-in berhasil.',
            'data' => $slot->fresh(),
        ]);
    }

    public function checkOut(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ClassId' => ['required', 'integer', 'exists:classes,id'],
            'Slot' => ['required', 'integer', 'min:1'],
        ]);

        $student = $this->resolveCurrentStudent($request);
        if (! $student) {
            return response()->json(['message' => 'User tidak valid.'], 401);
        }

        $slot = DetailClass::query()
            ->where('ClassId', $validated['ClassId'])
            ->where('Slot', $validated['Slot'])
            ->first();

        if (! $slot) {
            return response()->json(['message' => 'Slot tidak ditemukan.'], 404);
        }

        if ((int) $slot->StudentId !== (int) $student->getKey()) {
            return response()->json(['message' => 'Slot bukan milik user saat ini.'], 403);
        }

        $slot->update([
            'StudentId' => null,
        ]);

        return response()->json([
            'message' => 'Check-out berhasil.',
            'data' => $slot->fresh(),
        ]);
    }

    private function resolveCurrentStudent(Request $request): ?User
    {
        $authUser = $request->user();

        return $authUser instanceof User ? $authUser : null;
    }
}
