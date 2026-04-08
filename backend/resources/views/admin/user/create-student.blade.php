@extends('layouts.admin')

@section('title', 'Tambah Siswa')
@section('page-title', 'Kelola User – Tambah Siswa')

@section('content')
<div class="pt-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-lg">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <a href="{{ route('admin.user.index') }}"
               class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-[#1a2e5a] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                KEMBALI
            </a>
            <button form="form-student" type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold text-sm px-5 py-2 rounded-lg transition shadow-sm">
                SIMPAN
            </button>
        </div>

        <div class="px-6 py-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">TAMBAH SISWA</h3>

            <form id="form-student" method="POST" action="{{ route('admin.user.store-student') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">NAMA LENGKAP</label>
                    <input type="text" name="FullName" value="{{ old('FullName') }}" placeholder="Nama lengkap siswa"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               @error('FullName') border-red-400 @enderror" />
                    @error('FullName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">NIS</label>
                    <input type="number" name="NISNUPTK" value="{{ old('NISNUPTK') }}" placeholder="Nomor Induk Siswa"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               @error('NISNUPTK') border-red-400 @enderror" />
                    @error('NISNUPTK') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">KELAS</label>
                    <select name="ClassId"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm bg-white focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]">
                        <option value="">-- Pilih Kelas (opsional) --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('ClassId') == $class->id ? 'selected' : '' }}>
                                {{ $class->ClassName }} ({{ $class->RackName }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">EMAIL</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="email@siswa.sch.id"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               @error('email') border-red-400 @enderror" />
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">PASSWORD</label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               @error('password') border-red-400 @enderror" />
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">KONFIRMASI PASSWORD</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]" />
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
