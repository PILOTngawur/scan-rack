@extends('layouts.sidebar')

@section('title', 'Detail Rak – ' . $rak->RackName)
@section('page-title', 'Kelola Rak – Detail')

@section('content')
<div class="pt-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <a href="{{ route('admin.rak.index') }}"
               class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-[#1a2e5a] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                KEMBALI
            </a>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.rak.edit', $rak) }}"
                   class="bg-amber-500 hover:bg-amber-600 text-white font-semibold text-sm px-4 py-2 rounded-lg transition">
                    Edit Rak
                </a>

                <form method="POST" action="{{ route('admin.rak.destroy', $rak) }}"
                      onsubmit="return confirm('Hapus rak {{ $rak->RackName }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="text-sm bg-red-500 text-white font-semibold px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="flex flex-col md:flex-row gap-6 p-6">

            {{-- Left – Info & QR --}}
            <div class="md:w-52 shrink-0 space-y-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-medium mb-2">QR CODE</p>
                    <div class="bg-gray-100 rounded-xl p-3 flex items-center justify-center">
                        @if($rak->QrCode)
                            {{-- Render QR using a free API --}}
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($rak->QrCode) }}"
                                 alt="QR {{ $rak->RackName }}" class="w-36 h-36 rounded" />
                        @else
                            <div class="w-36 h-36 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs text-center">
                                Belum ada QR
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl border border-gray-200 p-4 space-y-3 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Nama Rak</p>
                        <p class="font-semibold text-gray-800">{{ $rak->RackName }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Kelas</p>
                        <p class="font-semibold text-gray-800">{{ $rak->ClassName ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Jumlah Slot</p>
                        <p class="font-semibold text-gray-800">{{ $rak->SlotTotal }} Slot</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Jumlah Slot</p>
                        <p class="font-semibold text-gray-800">{{ $rak->SlotTotal }} Slot</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Kosong</p>
                        <p class="font-semibold text-red-800">{{ $emptySlots }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-medium">Terisi</p>
                        <p class="font-semibold text-blue-800">{{ $usedSlots }}</p>
                    </div>
                </div>
            </div>

            {{-- Right – Slot Grid --}}
            <div class="flex-1">
                <p class="text-xs text-gray-500 uppercase font-medium mb-3">DETAIL SLOT</p>
            {{-- Legend --}}
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-green-400"></span>
                        <span class="text-xs text-gray-500">Terisi</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                        <span class="text-xs text-gray-500">Kosong</span>
                    </div>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($slots as $slot)
                        @php $isOccupied = ! is_null($slot->StudentId); @endphp
                        <div class="relative rounded-xl border-2 aspect-square flex flex-col items-center justify-center
                                    {{ $isOccupied ? 'border-red-400 bg-red-50' : 'border-[#00d4d4] bg-[#00d4d4]/10' }}
                                    transition cursor-default group"
                             title="{{ $isOccupied ? ($slot->student->FullName ?? 'Terisi') : 'Kosong' }}">
                            {{-- Status dot --}}
                            <span class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full
                                         {{ $isOccupied ? 'bg-green-400' : 'bg-red-500' }}"></span>

                            <span class="text-xl font-bold {{ $isOccupied ? 'text-red-600' : 'text-[#1a2e5a]' }}">
                                {{ $slot->Slot }}
                            </span>

                            @if($isOccupied)
                                <p class="text-[10px] text-black font-medium mt-0.5 px-1 text-center leading-tight truncate w-full">
                                    {{ Str::limit($slot->student->FullName ?? '', 10) }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>


            </div>

        </div>
    </div>
</div>
@endsection
