@extends('layouts.admin')

@section('title', 'Edit Rak')
@section('page-title', 'Kelola Rak – Edit')

@section('content')
<div class="pt-4">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden max-w-2xl">

        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <a href="{{ route('admin.rak.index') }}"
               class="inline-flex items-center gap-1 text-sm font-semibold text-gray-700 hover:text-[#1a2e5a] transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                KEMBALI
            </a>
            <button form="form-edit-rak" type="submit"
                class="bg-green-500 hover:bg-green-600 text-white font-semibold text-sm px-5 py-2 rounded-lg transition shadow-sm">
                SIMPAN
            </button>
        </div>

        <div class="px-6 py-5">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-4">EDIT RAK – {{ $rak->RackName }}</h3>

            <form id="form-edit-rak" method="POST" action="{{ route('admin.rak.update', $rak) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">NAMA RAK</label>
                            <input type="text" name="RackName" value="{{ old('RackName', $rak->RackName) }}"
                                placeholder="Contoh: Rak A"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]" />
                            @error('RackName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">KELAS RAK</label>
                            <select name="ClassName"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm bg-white focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach(['X RPL 1','X RPL 2','XI RPL 1','XI RPL 2','XII RPL 1','XII RPL 2',
                                          'X TKJ 1','XI TKJ 1','XII TKJ 1'] as $kelas)
                                    @php $selected = old('ClassName', $rak->ClassName) === $kelas ? 'selected' : ''; @endphp
                                    <option value="{{ $kelas }}" {{ $selected }}>{{ $kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1.5">TOTAL SLOT</label>
                            <input type="number" name="SlotTotal" value="{{ old('SlotTotal', $rak->SlotTotal) }}"
                                min="1" max="100"
                                class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none
                                       focus:ring-2 focus:ring-[#1a2e5a]/30 focus:border-[#1a2e5a]" />
                            @error('SlotTotal')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-400 mt-1">⚠️ Mengurangi slot akan menghapus slot yang sudah ada.</p>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="flex flex-col items-center">
                        <label class="block text-xs font-medium text-gray-600 mb-1.5 self-start">QR CODE</label>
                        @if($rak->QrCode)
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($rak->QrCode) }}"
                                 alt="QR" class="w-36 h-36 rounded-lg" />
                        @else
                            <div class="w-36 h-36 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400 text-xs">
                                Belum ada QR
                            </div>
                        @endif
                        <button type="button" onclick="generateQr()"
                            class="mt-2 text-xs bg-[#00d4d4] hover:bg-[#00a8a8] text-white px-3 py-1.5 rounded-lg transition">
                            GENERATE QR
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function generateQr() {
    fetch('{{ route('admin.rak.generate-qr', $rak) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        alert('QR Code baru berhasil digenerate.');
        location.reload();
    })
    .catch(err => alert('Gagal generate QR.'));
}
</script>
@endpush
