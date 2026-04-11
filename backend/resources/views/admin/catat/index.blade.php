@extends('layouts.admin')

@section('title', 'Catat Pengumpulan HP')
@section('page-title', 'Catat Pengumpulan HP')

@section('content')
<div class="pt-4 space-y-6">

	<div class="space-y-6">
			<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
				<div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
					<form method="GET" action="{{ route('admin.catat.index') }}" class="flex-1 space-y-3">
						<div>
							<label for="date" class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Rekap</label>
							<input
								id="date"
								type="date"
								name="date"
								value="{{ $selectedDate }}"
								class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-[#1a2e5a]" />
						</div>

						<div>
							<p class="text-xs font-semibold text-gray-600 uppercase mb-2">Filter Kelas (opsional)</p>
							<div class="grid grid-cols-2 md:grid-cols-3 gap-2">
								@foreach(collect($classOptions ?? [])->all() as $classItem)
									<label class="inline-flex items-center gap-2 text-sm text-gray-700">
										<input
											type="checkbox"
											name="class_ids[]"
											value="{{ $classItem->id }}"
											{{ in_array($classItem->id, $selectedClassIds, true) ? 'checked' : '' }}
											class="rounded border-gray-300 text-[#1a2e5a] focus:ring-[#1a2e5a]" />
										{{ $classItem->ClassName ?? '-' }}
									</label>
								@endforeach
							</div>
						</div>

						<button
							type="submit"
							class="bg-[#1a2e5a] hover:bg-[#132244] text-white font-semibold px-4 py-2 rounded-lg transition text-sm">
							Terapkan Filter
						</button>
					</form>

					<div class="flex flex-col sm:flex-row gap-2">
						<a
							href="{{ route('admin.catat.print', ['date' => $selectedDate]) }}"
							target="_blank"
							class="text-center bg-gray-800 hover:bg-gray-900 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
							Print Semua Kelas
						</a>

						<a
							href="{{ route('admin.catat.print', ['date' => $selectedDate, 'class_ids' => $selectedClassIds]) }}"
							target="_blank"
							class="text-center bg-[#5c9da4] hover:bg-[#4c8a91] text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
							Print Kelas Terpilih
						</a>
					</div>
				</div>
			</div>

			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
					<p class="text-xs font-semibold text-gray-500 uppercase">Jumlah Siswa Menitip Hari Ini</p>
					<p class="text-3xl font-bold text-[#1a2e5a] mt-2">{{ $totalStudentsCollected }}</p>
				</div>
				<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
					<p class="text-xs font-semibold text-gray-500 uppercase">Total Titipan Aktif</p>
					<p class="text-3xl font-bold text-[#1a2e5a] mt-2">{{ $totalPhonesCollected }}</p>
				</div>
			</div>

			<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-5 py-3 border-b border-gray-100 flex items-center justify-between">
					<p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Rekap per Kelas</p>
					<span class="text-xs text-gray-500">Berdasarkan slot rak terisi pada {{ \Illuminate\Support\Carbon::parse($selectedDate)->format('d M Y') }}</span>
				</div>

				<table class="w-full text-sm">
					<thead class="bg-gray-50">
						<tr>
							<th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Kelas</th>
							<th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Siswa Mengumpulkan</th>
							<th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Waktu Terakhir Menaruh</th>
						</tr>
					</thead>
					<tbody class="divide-y divide-gray-100">
						@forelse($classSummary as $summary)
							<tr>
								<td class="px-5 py-3 text-[#1a2e5a] font-medium">{{ $summary->class->ClassName ?? 'Tanpa Kelas' }}</td>
								<td class="px-5 py-3 text-gray-700">{{ $summary->students_count }}</td>
								<td class="px-5 py-3 text-gray-700">{{ $summary->last_put_at ? \Illuminate\Support\Carbon::parse($summary->last_put_at)->timezone('Asia/Jakarta')->format('H:i') : '-' }} WIB</td>
							</tr>
						@empty
							<tr>
								<td colspan="3" class="px-5 py-6 text-center text-gray-400 text-xs">Belum ada data pada tanggal ini.</td>
							</tr>
						@endforelse
					</tbody>
				</table>
			</div>

			<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
				<div class="px-5 py-3 border-b border-gray-100">
					<p class="text-xs font-bold text-gray-700 uppercase tracking-wide">Detail Slot Rak Terisi</p>
				</div>
				<div class="p-4 space-y-4">
					@forelse($classGroups as $group)
						<div class="border border-gray-200 rounded-lg overflow-hidden">
							<div class="bg-gray-50 px-4 py-2 flex items-center justify-between">
								<p class="text-sm font-semibold text-[#1a2e5a]">
									{{ $group['class_name'] }}
									<span class="text-gray-400 font-normal">• Rak {{ $group['rack_name'] }}</span>
								</p>
								<span class="text-xs font-semibold text-gray-600">{{ $group['total'] }} siswa</span>
							</div>

							<table class="w-full text-sm">
								<thead class="bg-white">
									<tr>
										<th class="text-left px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Slot</th>
										<th class="text-left px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
										<th class="text-left px-4 py-2 text-xs font-semibold text-gray-500 uppercase">Waktu Menaruh</th>
									</tr>
								</thead>
								<tbody class="divide-y divide-gray-100">
									@foreach($group['records'] as $record)
										<tr>
											<td class="px-4 py-2.5 text-gray-700">#{{ $record->Slot }}</td>
											<td class="px-4 py-2.5 text-[#1a2e5a] font-medium">{{ $record->student->FullName ?? '-' }}</td>
											<td class="px-4 py-2.5 text-gray-700">{{ optional($record->updated_at)?->timezone('Asia/Jakarta')->format('H:i') ?? '-' }} WIB</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					@empty
						<div class="px-5 py-8 text-center text-gray-400 text-sm border border-dashed border-gray-200 rounded-lg">
							Belum ada slot rak terisi pada tanggal ini.
						</div>
					@endforelse
				</div>
			</div>
	</div>
</div>
@endsection

