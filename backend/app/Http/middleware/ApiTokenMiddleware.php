<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractBearerToken($request);

        if (! $token) {
            return $this->unauthorized('Token tidak ditemukan.');
        }

        $user = User::query()
            ->where('remember_token', $token)
            ->where('Role', 'student')
            ->first();

        if (! $user) {
            return $this->unauthorized('Token tidak valid.');
        }

        $request->attributes->set('api_user', $user);

        return $next($request);
    }

    private function extractBearerToken(Request $request): ?string
    {
        $header = (string) $request->header('Authorization', '');

        if (! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return trim(substr($header, 7)) ?: null;
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }
}
