@extends('layouts.app')

@section('title', 'Master Data Grade Kualitas - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-pink-700 mb-1">
                <span>Master Data</span>
                <span>&bull;</span>
                <span>Standar Mutu Fisik</span>
            </div>
            <h1 class="text-2xl font-bold text-[#2D232A]">Master Grade Kualitas Limbah</h1>
            <p class="text-xs text-[#7E6E78]">Kelola bobot pengali poin berdasarkan kondisi fisik sampah (A, B, C, D)</p>
        </div>
        <div>
            <button type="button" onclick="openAddGradeModal()" class="px-4 py-2.5 rounded-xl btn-primary text-xs font-semibold shadow-md shadow-pink-200 flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Grade Kualitas</span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden border border-pink-100">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-pink-50/40 border-b border-pink-100 text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">Kode Grade</th>
                        <th class="py-3.5 px-4">Nama Klasifikasi Kualitas</th>
                        <th class="py-3.5 px-4">Bobot Pengali (Multiplier)</th>
                        <th class="py-3.5 px-4">Kriteria Fisik</th>
                        <th class="py-3.5 px-4">Total Pemakaian</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-pink-50">
                    @forelse($qualityGrades as $grade)
                        <tr class="hover:bg-pink-50/40 transition">
                            <td class="py-3.5 px-4">
                                <span class="w-8 h-8 rounded-lg bg-[#FFF0F3] text-[#E05780] font-black text-sm flex items-center justify-center border border-pink-200">
                                    {{ $grade->grade_code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-gray-800 text-sm">
                                {{ $grade->grade_name }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-black bg-pink-50 text-[#BE185D] border border-pink-200">
                                    {{ $grade->point_multiplier }}× Poin
                                </span>
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-gray-500" title="{{ $grade->description }}">
                                {{ $grade->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-600">
                                {{ number_format($grade->transactions_count) }} Transaksi
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <button 
                                    type="button" 
                                    onclick="openEditGradeModal({{ $grade->id }}, '{{ addslashes($grade->grade_code) }}', '{{ addslashes($grade->grade_name) }}', {{ $grade->point_multiplier }}, '{{ addslashes($grade->description ?? '') }}')"
                                    class="px-2.5 py-1 rounded-lg border border-pink-200 text-gray-700 text-xs font-semibold hover:bg-pink-50 hover:text-[#E05780] transition"
                                >
                                    Edit
                                </button>
                                
                                @if($grade->transactions_count == 0)
                                    <form action="{{ route('admin.master.quality-grades.destroy', $grade) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus grade ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition ml-1">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-400">Belum ada grade kualitas yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Grade -->
<div id="addGradeModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Tambah Grade Kualitas</h3>
        <form action="{{ route('admin.master.quality-grades.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Grade (Contoh: A, B, C, D) <span class="text-red-500">*</span></label>
                <input type="text" name="grade_code" required maxlength="5" placeholder="A" class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Klasifikasi Mutu <span class="text-red-500">*</span></label>
                <input type="text" name="grade_name" required placeholder="Contoh: Sangat Bagus" class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Pengali Poin (Multiplier) <span class="text-red-500">*</span></label>
                <input type="number" name="point_multiplier" value="1" min="1" required class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
                <p class="text-[11px] text-gray-400 mt-1">Sesuai PRD: A=4, B=3, C=2, D=1.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Kondisi Fisik</label>
                <textarea name="description" rows="2" placeholder="Kondisi fisik sampah yang masuk kriteria ini..." class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeAddGradeModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl btn-primary text-xs font-semibold shadow-xs">Simpan Grade</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Grade -->
<div id="editGradeModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Ubah Grade Kualitas</h3>
        <form id="editGradeForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Grade <span class="text-red-500">*</span></label>
                <input type="text" id="edit_grade_code" name="grade_code" required maxlength="5" class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Klasifikasi Mutu <span class="text-red-500">*</span></label>
                <input type="text" id="edit_grade_name" name="grade_name" required class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nilai Pengali Poin <span class="text-red-500">*</span></label>
                <input type="number" id="edit_grade_multiplier" name="point_multiplier" min="1" required class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Kondisi Fisik</label>
                <textarea id="edit_grade_desc" name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-pink-200 text-xs focus:ring-2 focus:ring-[#E05780]"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeEditGradeModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl btn-primary text-xs font-semibold shadow-xs">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddGradeModal() {
        document.getElementById('addGradeModal').classList.remove('hidden');
    }
    function closeAddGradeModal() {
        document.getElementById('addGradeModal').classList.add('hidden');
    }

    function openEditGradeModal(id, code, name, mult, desc) {
        document.getElementById('editGradeForm').action = "{{ url('admin/master/quality-grades') }}/" + id;
        document.getElementById('edit_grade_code').value = code;
        document.getElementById('edit_grade_name').value = name;
        document.getElementById('edit_grade_multiplier').value = mult;
        document.getElementById('edit_grade_desc').value = desc;
        document.getElementById('editGradeModal').classList.remove('hidden');
    }
    function closeEditGradeModal() {
        document.getElementById('editGradeModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
