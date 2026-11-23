@extends('layouts.app')

@section('title', 'Master Data Jenis Limbah - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-xs text-gray-500 mb-1">
                <span>Master Data</span>
                <span>&bull;</span>
                <span>Kategori Sampah</span>
            </div>
            <h1 class="text-2xl font-bold text-[#212121]">Master Data Jenis Limbah</h1>
            <p class="text-xs text-[#757575]">Kelola jenis sampah yang diterima, satuan (pcs/gram), dan konversi dasar poin</p>
        </div>
        <div>
            <button type="button" onclick="openAddWasteModal()" class="px-4 py-2.5 rounded-xl btn-primary text-xs font-semibold shadow-sm flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tambah Jenis Limbah</span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="py-3.5 px-4">ID</th>
                        <th class="py-3.5 px-4">Nama Jenis Sampah</th>
                        <th class="py-3.5 px-4">Satuan Ukur</th>
                        <th class="py-3.5 px-4">Rasio Konversi Dasar</th>
                        <th class="py-3.5 px-4">Keterangan</th>
                        <th class="py-3.5 px-4">Transaksi Terkait</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($wasteTypes as $type)
                        <tr class="hover:bg-gray-50/60 transition">
                            <td class="py-3.5 px-4 font-bold text-gray-400">#{{ $type->id }}</td>
                            <td class="py-3.5 px-4 font-bold text-gray-800">{{ $type->name }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded text-[11px] font-semibold {{ $type->unit_type == 'pcs' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $type->unit_type == 'pcs' ? 'Buah / Pcs' : 'Gram' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-[#2E7D32]">
                                {{ $type->base_point_conversion }} {{ $type->unit_type }} = 1 Poin Dasar
                            </td>
                            <td class="py-3.5 px-4 max-w-xs text-gray-500 truncate" title="{{ $type->description }}">
                                {{ $type->description ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-600">
                                {{ number_format($type->transactions_count) }} Data
                            </td>
                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                <button 
                                    type="button" 
                                    onclick="openEditWasteModal({{ $type->id }}, '{{ addslashes($type->name) }}', '{{ $type->unit_type }}', {{ $type->base_point_conversion }}, '{{ addslashes($type->description ?? '') }}')"
                                    class="px-2.5 py-1 rounded-lg border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-50 transition"
                                >
                                    Edit
                                </button>
                                
                                @if($type->transactions_count == 0)
                                    <form action="{{ route('admin.master.waste-types.destroy', $type) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus jenis limbah ini?')">
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
                            <td colspan="7" class="py-8 text-center text-gray-400">Belum ada jenis limbah yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Jenis Limbah -->
<div id="addWasteModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Tambah Jenis Limbah Baru</h3>
        <form action="{{ route('admin.master.waste-types.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Limbah <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Contoh: Botol Kaca" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Ukur <span class="text-red-500">*</span></label>
                <select name="unit_type" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
                    <option value="pcs">pcs (Dihitung per buah)</option>
                    <option value="gram">gram (Dihitung berdasarkan berat gram)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Konversi Dasar untuk 1 Poin <span class="text-red-500">*</span></label>
                <input type="number" name="base_point_conversion" value="1" min="1" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
                <p class="text-[11px] text-gray-400 mt-1">Misal: 1 untuk botol pcs, 100 untuk kaleng gram.</p>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi / Kriteria</label>
                <textarea name="description" rows="2" placeholder="Kriteria kondisi yang diterima..." class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeAddWasteModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl btn-primary text-xs font-semibold">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Jenis Limbah -->
<div id="editWasteModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Ubah Data Jenis Limbah</h3>
        <form id="editWasteForm" method="POST" action="" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Limbah <span class="text-red-500">*</span></label>
                <input type="text" id="edit_waste_name" name="name" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Satuan Ukur <span class="text-red-500">*</span></label>
                <select id="edit_waste_unit" name="unit_type" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
                    <option value="pcs">pcs (Dihitung per buah)</option>
                    <option value="gram">gram (Dihitung berdasarkan berat gram)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Konversi Dasar untuk 1 Poin <span class="text-red-500">*</span></label>
                <input type="number" id="edit_waste_base" name="base_point_conversion" min="1" required class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi</label>
                <textarea id="edit_waste_desc" name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-[#2E7D32]"></textarea>
            </div>
            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" onclick="closeEditWasteModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl btn-primary text-xs font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openAddWasteModal() {
        document.getElementById('addWasteModal').classList.remove('hidden');
    }
    function closeAddWasteModal() {
        document.getElementById('addWasteModal').classList.add('hidden');
    }

    function openEditWasteModal(id, name, unit, base, desc) {
        document.getElementById('editWasteForm').action = "{{ url('admin/master/waste-types') }}/" + id;
        document.getElementById('edit_waste_name').value = name;
        document.getElementById('edit_waste_unit').value = unit;
        document.getElementById('edit_waste_base').value = base;
        document.getElementById('edit_waste_desc').value = desc;
        document.getElementById('editWasteModal').classList.remove('hidden');
    }
    function closeEditWasteModal() {
        document.getElementById('editWasteModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
