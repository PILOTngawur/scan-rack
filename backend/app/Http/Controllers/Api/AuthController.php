<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MasterStudent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function masterStudentByNis(string $nis): JsonResponse
    {
        $masterStudent = MasterStudent::query()
            ->where('NIS', $nis)
            ->first();

        if (! $masterStudent || ! $masterStudent->FullName) {
            return response()->json([
                'message' => 'NIS tidak ada.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'NIS' => (int) $masterStudent->NIS,
                'FullName' => $masterStudent->FullName,
            ],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ClassId' => ['required', 'integer', 'exists:classes,id'],
            'NISNUPTK' => ['required', 'integer', 'exists:master_students,NIS', 'unique:users,NISNUPTK'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'NISNUPTK.exists' => 'NIS tidak ada.',
            'NISNUPTK.unique' => 'NIS sudah terdaftar.',
        ]);

        $masterStudent = MasterStudent::query()
            ->where('NIS', $validated['NISNUPTK'])
            ->first();

        if (! $masterStudent || ! $masterStudent->FullName) {
            return response()->json([
                'message' => 'NIS tidak ditemukan pada data master siswa.',
            ], 422);
        }

        $user = User::create([
            'FullName' => $masterStudent->FullName,
            'ClassId' => $validated['ClassId'],
            'NISNUPTK' => $validated['NISNUPTK'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'Role' => 'student',
        ]);

        $token = bin2hex(random_bytes(32));
        $user->forceFill([
            'remember_token' => $token,
        ])->save();

        return response()->json([
            'message' => 'Registrasi berhasil.',
            'token' => $token,
            'user' => $user->fresh(['class']),
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah.',
            ], 401);
        }

        if ($user->Role !== 'student') {
            return response()->json([
                'message' => 'Akun ini bukan akun siswa.',
            ], 403);
        }

        $token = bin2hex(random_bytes(32));
        $user->forceFill([
            'remember_token' => $token,
        ])->save();

        $user->load('class');

        return response()->json([
            'message' => 'Login berhasil.',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->attributes->get('api_user');

        if ($user) {
            $user->forceFill([
                'remember_token' => null,
            ])->save();
        }

        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->attributes->get('api_user');

        return response()->json([
            'user' => $user?->load('class'),
        ]);
    }

    public function updateDeviceName(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        /** @var User|null $user */
        $user = $request->attributes->get('api_user');

        if (! $user) {
            return response()->json([
                'message' => 'User tidak valid.',
            ], 401);
        }

        $user->forceFill([
            'DeviceName' => $validated['device_name'],
        ])->save();

        return response()->json([
            'message' => 'Tipe handphone berhasil diperbarui.',
            'user' => $user->fresh(['class']),
        ]);
    }
}
