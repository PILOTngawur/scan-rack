@extends('layouts.admin')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')

@section('content')
<div class="pt-4 space-y-6">

    {{-- ── DATA PENGELOLA ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">DATA PENGELOLA</p>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="student_search" value="{{ request('student_search') }}" />
                    <input type="text" name="admin_search" value="{{ request('admin_search') }}"
                        placeholder="Cari..."
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-[#1a2e5a]" />
                    <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">Search</button>
                </form>
                <a href="{{ route('admin.user.create-admin') }}"
                   class="text-xs bg-[#00d4d4] hover:bg-[#00a8a8] text-white font-semibold px-3 py-1.5 rounded-lg transition">
                    + Tambah
                </a>
            </div>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Nama <span class="text-gray-300">↕</span></th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($admins as $admin)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-[#1a2e5a] font-medium">{{ $admin->FullName }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $admin->email }}</td>
                        <td class="px-5 py-3 text-center">
                            @if($admin->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.user.destroy-admin', $admin) }}"
                                      onsubmit="return confirm('Hapus pengelola {{ $admin->FullName }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition font-medium">
                                        DELETE
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400 italic">Anda</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-6 text-center text-gray-400 text-xs">Belum ada pengelola.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($admins->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ $admins->firstItem() }}–{{ $admins->lastItem() }} dari {{ $admins->total() }}</span>
                <div class="flex items-center gap-1">
                    @if($admins->onFirstPage())
                        <span class="px-2 py-1 text-xs text-gray-300">«</span>
                    @else
                        <a href="{{ $admins->previousPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">«</a>
                    @endif
                    @foreach($admins->getUrlRange(1, $admins->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="px-2 py-1 text-xs rounded {{ $page == $admins->currentPage() ? 'bg-[#1a2e5a] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    @if($admins->hasMorePages())
                        <a href="{{ $admins->nextPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">»</a>
                    @else
                        <span class="px-2 py-1 text-xs text-gray-300">»</span>
                    @endif
                </div>
                {{-- Per page selector placeholder --}}
                <select class="text-xs border border-gray-200 rounded px-2 py-1">
                    <option>5</option><option>10</option><option>25</option>
                </select>
            </div>
        @endif
    </div>

    {{-- ── DATA NISN ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">DATA NIS</p>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="admin_search" value="{{ request('admin_search') }}" />
                    <input type="hidden" name="student_search" value="{{ request('student_search') }}" />
                    <input type="text" name="nis_search" value="{{ request('nis_search') }}"
                        placeholder="Cari..."
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-[#1a2e5a]" />
                    <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">Search</button>
                </form>
                <a href="{{ route('admin.user.create-nis') }}"
                   class="text-xs bg-[#00d4d4] hover:bg-[#00a8a8] text-white font-semibold px-3 py-1.5 rounded-lg transition">
                    + Tambah
                </a>
            </div>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Nama <span class="text-gray-300">↕</span></th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">NIS</th>
                    <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($masterStudents as $masterStudent)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-[#1a2e5a] font-medium">{{ $masterStudent->FullName ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $masterStudent->NIS }}</td>
                        <td class="px-5 py-3 text-center">
                            <form method="POST" action="{{ route('admin.user.destroy-nis', $masterStudent) }}"
                                  onsubmit="return confirm('Hapus data NIS {{ $masterStudent->NIS }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition font-medium">
                                    DELETE
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-5 py-6 text-center text-gray-400 text-xs">Belum ada data NIS terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($masterStudents->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ $masterStudents->firstItem() }}–{{ $masterStudents->lastItem() }} dari {{ $masterStudents->total() }}</span>
                <div class="flex items-center gap-1">
                    @if($masterStudents->onFirstPage())
                        <span class="px-2 py-1 text-xs text-gray-300">«</span>
                    @else
                        <a href="{{ $masterStudents->previousPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">«</a>
                    @endif
                    @foreach($masterStudents->getUrlRange(1, $masterStudents->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="px-2 py-1 text-xs rounded {{ $page == $masterStudents->currentPage() ? 'bg-[#1a2e5a] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    @if($masterStudents->hasMorePages())
                        <a href="{{ $masterStudents->nextPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">»</a>
                    @else
                        <span class="px-2 py-1 text-xs text-gray-300">»</span>
                    @endif
                </div>
                {{-- Per page selector placeholder --}}
                <select class="text-xs border border-gray-200 rounded px-2 py-1">
                    <option>5</option><option>10</option><option>25</option>
                </select>
            </div>
        @endif
    </div>

    {{-- ── DATA SISWA ── --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <p class="text-xs font-bold text-gray-700 uppercase tracking-wide">DATA SISWA</p>
            <div class="flex items-center gap-3">
                <form method="GET" action="{{ route('admin.user.index') }}" class="flex items-center gap-2">
                    <input type="hidden" name="admin_search" value="{{ request('admin_search') }}" />
                    <input type="text" name="student_search" value="{{ request('student_search') }}"
                        placeholder="Cari..."
                        class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-1 focus:ring-[#1a2e5a]" />
                    <button type="submit" class="text-xs bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">Search</button>
                </form>
                <a href="{{ route('admin.user.create-student') }}"
                   class="text-xs bg-[#00d4d4] hover:bg-[#00a8a8] text-white font-semibold px-3 py-1.5 rounded-lg transition">
                    + Tambah
                </a>
            </div>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Nama <span class="text-gray-300">↕</span></th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Kelas <span class="text-gray-300">↕</span></th>
                    <th class="text-left px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Email</th>
                    <th class="text-center px-5 py-2.5 text-xs font-semibold text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 text-[#1a2e5a] font-medium">{{ $student->FullName }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $student->class->ClassName ?? '-' }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $student->email }}</td>
                        <td class="px-5 py-3 text-center">
                            <form method="POST" action="{{ route('admin.user.destroy-student', $student) }}"
                                  onsubmit="return confirm('Hapus siswa {{ $student->FullName }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg transition font-medium">
                                    DELETE
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-gray-400 text-xs">Belum ada data siswa.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($students->hasPages())
            <div class="px-5 py-3 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">{{ $students->firstItem() }}–{{ $students->lastItem() }} dari {{ $students->total() }}</span>
                <div class="flex items-center gap-1">
                    @if($students->onFirstPage())
                        <span class="px-2 py-1 text-xs text-gray-300">«</span>
                    @else
                        <a href="{{ $students->previousPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">«</a>
                    @endif
                    @foreach($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                        <a href="{{ $url }}"
                           class="px-2 py-1 text-xs rounded {{ $page == $students->currentPage() ? 'bg-[#1a2e5a] text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    @if($students->hasMorePages())
                        <a href="{{ $students->nextPageUrl() }}" class="px-2 py-1 text-xs text-gray-600 hover:text-[#1a2e5a]">»</a>
                    @else
                        <span class="px-2 py-1 text-xs text-gray-300">»</span>
                    @endif
                </div>
                <select class="text-xs border border-gray-200 rounded px-2 py-1">
                    <option>5</option><option>10</option><option>25</option>
                </select>
            </div>
        @endif
    </div>

</div>
@endsection
