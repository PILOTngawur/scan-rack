@extends('layouts.admin')

@section('title', 'Tambah Data NIS')
@section('page-title', 'Kelola User – Tambah Data NIS')

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
            <button form="form-nis" type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold text-sm px-5 py-2 rounded-lg transition shadow-sm">
                SIMPAN
            </button>
        </div>

        <div class="px-6 py-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">TAMBAH DATA NIS</h3>

            <form id="form-nis" method="POST" action="{{ route('admin.user.store-nis') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">NAMA LENGKAP SISWA</label>
                    <input type="text" name="FullName" value="{{ old('FullName') }}" placeholder="Nama lengkap siswa"
                        class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               @error('FullName') border-red-400 @enderror
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               " />
                    @error('FullName') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1.5">NIS</label>
                    <input type="number" name="NIS" value="{{ old('NIS') }}" placeholder="Nomor Induk Siswa"
                        class="w-full border rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                               @error('NIS') border-red-400 @enderror
                               focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                               " />
                    @error('NIS') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
