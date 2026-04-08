<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login – Phone Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-[#1a2e5a] flex items-center justify-center px-4">

<div class="w-full max-w-sm">
    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

        {{-- Header strip --}}
        <div class="bg-[#1a2e5a] px-8 py-7 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-white/10 mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 class="text-white font-bold text-xl tracking-wide">Phone Collection</h1>
            <p class="text-white/60 text-xs mt-1">Sistem Pengumpulan Handphone</p>
        </div>

        {{-- Form --}}
        <div class="px-8 py-7">
            <h2 class="text-gray-800 font-semibold text-base mb-5">Masuk sebagai Admin</h2>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                    @foreach($errors->all() as $error)
                        <p class="text-red-700 text-xs">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-600 mb-1.5">
                        Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="admin@sekolah.sch.id"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-[#1a2e5a]/40 focus:border-[#1a2e5a]
                               @error('email') border-red-400 @enderror transition"
                    />
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-600 mb-1.5">
                        Password
                    </label>
                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="••••••••"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-[#1a2e5a]/40 focus:border-[#1a2e5a]
                                   @error('password') border-red-400 @enderror transition pr-10"
                        />
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-[#1a2e5a] focus:ring-[#1a2e5a]/40 cursor-pointer" />
                    <label for="remember" class="text-xs text-gray-600 cursor-pointer select-none">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-[#1a2e5a] hover:bg-[#132244] text-white font-semibold text-sm py-2.5 rounded-lg
                           transition duration-200 active:scale-[0.98] mt-2 shadow-md">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-white/40 text-xs mt-5">
        © {{ date('Y') }} Phone Collection System
    </p>
</div>

<script>
    function togglePassword() {
        const input = document.getElementById('password');
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>
