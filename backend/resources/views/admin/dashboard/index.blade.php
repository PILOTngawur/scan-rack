@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard – Admin')

@section('content')
<div class="pt-4 space-y-6">

    {{-- Welcome Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 flex items-center gap-4">
    <div class="w-10 h-10 rounded-full bg-[#1a2e5a] flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-gray-500">Selamat Datang,</p>
            <p class="text-base font-bold text-gray-800">{{ strtoupper(auth()->user()->FullName ?? 'ADMIN') }}</p>
        </div>
    </div>

    {{-- Total Devices Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-3">Total Handphone Terkumpul</p>
        <div class="flex items-center justify-center gap-4 bg-[#00d4d4] rounded-xl py-5">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span class="text-5xl font-extrabold text-white">{{ $totalDevices }}</span>
        </div>
    </div>

    {{-- Rak Overview --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Daftar Rak</p>
            <a href="{{ route('admin.rak.index') }}" class="text-xs text-[#1a2e5a] font-medium hover:underline">
                Lihat Semua →
            </a>
        </div>

        @if($racks->isEmpty())
            <p class="text-center text-sm text-gray-400 py-4">Belum ada rak. <a href="{{ route('admin.rak.create') }}" class="text-[#1a2e5a] underline">Tambah rak</a>.</p>
        @else
            <div class="flex gap-5 overflow-x-auto pb-2">
                @foreach($racks as $rack)
                    <a href="{{ route('admin.rak.show', $rack) }}"
              class="shrink-0 flex flex-col items-center justify-between
                              rounded-xl border-2 border-[#00d4d4] bg-[#00d4d4]
                              w-40 py-3 px-2 hover:bg-[#00a8a8] hover:border-[#00a8a8] transition group">
                        <span class="text-white font-bold text-lg text-center leading-tight">
                            {{ $rack->RackName }}
                        </span>
                        <span class="text-black font-semibold text-sm text-center leading-tight">
                            {{ $rack->ClassName }}
                        </span>
                        <span class="mt-10 bg-[#4C505C] rounded-lg w-full text-center py-1 text-xs font-bold text-white">
                            {{ $rack->used_slots ?? 0 }}
                        </span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
