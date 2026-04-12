@extends('layouts.sidebar')

@section('title', 'Kelola Rak')
@section('page-title', 'Kelola Rak')

@section('content')
<div class="pt-4 space-y-4">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h2 class="text-base font-bold text-gray-800">Daftar Rak</h2>
        <a href="{{ route('admin.rak.create') }}"
           class="inline-flex items-center gap-1.5 bg-[#00d4d4] hover:bg-[#00a8a8] text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            TAMBAH
        </a>
    </div>

    {{-- Rak Grid --}}
    @if($racks->isEmpty())
        <div class="bg-white rounded-xl border border-dashed border-gray-300 p-12 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-gray-500 text-sm">Belum ada rak.</p>
            <a href="{{ route('admin.rak.create') }}" class="mt-2 inline-block text-sm text-[#1a2e5a] font-medium hover:underline">
                Tambah rak pertama →
            </a>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
            <div class="flex flex-wrap gap-3">
                @foreach($racks as $rack)
                    <a href="{{ route('admin.rak.show', $rack) }}"
                       class="flex flex-col items-center justify-between rounded-xl border-2 border-[#00d4d4] bg-[#00d4d4]
                              w-40 py-3 px-2 hover:bg-[#00a8a8] hover:border-[#00a8a8] transition group">
                        <span class="text-white font-bold text-sm text-center leading-tight">
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
        </div>
    @endif

</div>
@endsection
