<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClassModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RakApiController extends Controller
{
    public function classes(): JsonResponse
    {
        $classes = ClassModel::query()
            ->select(['id', 'ClassName'])
            ->orderBy('ClassName')
            ->get();

        return response()->json([
            'data' => $classes,
        ]);
    }

    public function index(): JsonResponse
    {
        $racks = ClassModel::query()
            ->withCount([
                'detailClasses as used_slots' => fn ($query) => $query->whereNotNull('StudentId'),
                'detailClasses as empty_slots' => fn ($query) => $query->whereNull('StudentId'),
            ])
            ->get();

        return response()->json([
            'data' => $racks,
        ]);
    }

    public function scanQr(Request $request, string $qrCode): JsonResponse
    {
        $student = $request->attributes->get('api_user');

        if (! $student instanceof User) {
            return response()->json([
                'message' => 'User tidak valid.',
            ], 401);
        }

        $rack = ClassModel::query()
            ->where('QrCode', $qrCode)
            ->with([
                'detailClasses' => fn ($query) => $query->with('student')->orderBy('Slot'),
            ])
            ->first();

        if (! $rack) {
            return response()->json([
                'message' => 'Rak tidak ditemukan.',
            ], 404);
        }

        if ((int) $student->ClassId !== (int) $rack->getKey()) {
            return response()->json([
                'message' => 'Rak tidak sesuai dengan kelas siswa.',
            ], 403);
        }

        return response()->json([
            'data' => $rack,
        ]);
    }
}
