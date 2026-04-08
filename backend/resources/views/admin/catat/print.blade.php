<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Rekap Pengumpulan HP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 24px;
        }
        h1, h2, p {
            margin: 0;
        }
        .header {
            margin-bottom: 16px;
        }
        .meta {
            margin-top: 6px;
            font-size: 13px;
            color: #4b5563;
        }
        .summary {
            display: flex;
            gap: 24px;
            margin: 16px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.03em;
        }
        .text-center {
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 12px;">
        <button onclick="window.print()" style="padding: 8px 12px; border: 0; border-radius: 6px; background: #1f2937; color: white; cursor: pointer;">
            Print Sekarang
        </button>
    </div>

    <div class="header">
        <h1>Rekap Pengumpulan HP Siswa</h1>
        <p class="meta">Tanggal: {{ \Illuminate\Support\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}</p>
        <p class="meta">
            Kelas:
            @if(empty($selectedClassIds))
                Semua Kelas
            @else
                {{ collect($classOptions ?? [])->whereIn('id', $selectedClassIds)->pluck('ClassName')->filter()->join(', ') ?: 'Pilihan Kelas' }}
            @endif
        </p>
    </div>

    <div class="summary">
        <div><strong>Total Titipan Aktif:</strong> {{ $totalPhonesCollected }}</div>
    </div>

    @forelse($classGroups as $group)
        <h2 style="margin-top: 16px; font-size: 14px;">
            Kelas {{ $group['class_name'] }} — Rak {{ $group['rack_name'] }}
            <span style="font-weight: normal; color: #4b5563;">(Total: {{ $group['total'] }} siswa)</span>
        </h2>
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Slot</th>
                    <th>Nama Siswa</th>
                    <th>Waktu Menaruh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($group['records'] as $index => $record)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>#{{ $record->Slot }}</td>
                        <td>{{ $record->student->FullName ?? '-' }}</td>
                        <td>{{ optional($record->updated_at)->format('H:i') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @empty
        <table>
            <tbody>
                <tr>
                    <td class="text-center">Belum ada data slot rak terisi pada filter ini.</td>
                </tr>
            </tbody>
        </table>
    @endforelse

    <script>
        window.addEventListener('load', function () {
            window.print();
        });
    </script>
</body>
</html>
