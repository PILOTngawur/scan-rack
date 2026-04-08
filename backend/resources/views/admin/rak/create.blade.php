@extends('layouts.admin')

@section('title', 'Tambah Rak')
@section('page-title', 'Kelola Rak – Tambah')

@section('content')
<div class="pt-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-2xl">

        {{-- Card Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <a href="{{ route('admin.rak.index') }}"
               class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-[#1a2e5a] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                KEMBALI
            </a>
            <button form="form-rak" type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold text-sm px-5 py-2 rounded-lg transition shadow-sm">
                SIMPAN
            </button>
        </div>

        <div class="px-6 py-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">TAMBAH RAK</h3>

            <form id="form-rak" method="POST" action="{{ route('admin.rak.store') }}" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Left column --}}
                    <div class="space-y-4">
                        {{-- Nama Rak --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">NAMA RAK</label>
                            <input type="text" name="RackName" value="{{ old('RackName') }}"
                                placeholder="Contoh: Rak A"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                                       @error('RackName') border-red-400 @enderror" />
                            @error('RackName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kelas Rak --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">KELAS RAK</label>
                            <select name="ClassName"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm bg-white focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                                       @error('ClassName') border-red-400 @enderror">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(['X RPL 1','X RPL 2','XI RPL 1','XI RPL 2','XII RPL 1','XII RPL 2',
                                          'X TKJ 1','XI TKJ 1','XII TKJ 1'] as $kelas)
                                    <option value="{{ $kelas }}" {{ old('ClassName') === $kelas ? 'selected' : '' }}>
                                        {{ $kelas }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ClassName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Total Slot --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">TOTAL SLOT</label>
                            <input type="number" name="SlotTotal" value="{{ old('SlotTotal', 20) }}"
                                min="1" max="100"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]
                                       @error('SlotTotal') border-red-400 @enderror" />
                            @error('SlotTotal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right column – QR Code preview --}}
                    <div class="flex flex-col items-center">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5 self-start">QR CODE</label>
                        <div id="qr-preview"
                             class="w-36 h-36 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs text-center">
                            <span>QR akan digenerate<br>setelah disimpan</span>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
