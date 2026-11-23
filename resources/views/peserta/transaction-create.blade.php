@extends('layouts.app')

@section('title', 'Setor Penukaran Limbah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('peserta.transactions') }}" class="text-xs text-gray-500 hover:text-[#E05780] flex items-center mb-1">
                &larr; Kembali ke Riwayat
            </a>
            <h1 class="text-2xl font-bold text-[#2D232A]">Setor Limbah Sampah</h1>
            <p class="text-xs text-[#7E6E78]">Catat penukaran botol atau kaleng untuk mendapatkan poin</p>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card-eco p-6 md:p-8 bg-white border border-pink-100">
        <form action="{{ route('peserta.transactions.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Jenis Limbah -->
            <div>
                <label for="waste_type_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    1. Pilih Jenis Limbah <span class="text-red-500">*</span>
                </label>
                <select 
                    name="waste_type_id" 
                    id="waste_type_id" 
                    required 
                    onchange="updateCalculations()"
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                >
                    <option value="" disabled selected>-- Pilih Jenis Limbah --</option>
                    @foreach($wasteTypes as $type)
                        <option 
                            value="{{ $type->id }}" 
                            data-unit="{{ $type->unit_type }}"
                            data-base="{{ $type->base_point_conversion }}"
                            {{ old('waste_type_id') == $type->id ? 'selected' : '' }}
                        >
                            {{ $type->name }} (Satuan: {{ $type->unit_type == 'pcs' ? 'Buah / Pcs' : 'Gram' }})
                        </option>
                    @endforeach
                </select>
                <p id="waste_desc_help" class="text-xs text-gray-400 mt-1">
                    Pilih botol (dihitung per buah) atau kaleng (dihitung per berat gram).
                </p>
            </div>

            <!-- 2. Grade Kualitas -->
            <div>
                <label for="quality_grade_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    2. Kondisi Fisik / Grade Kualitas <span class="text-red-500">*</span>
                </label>
                <select 
                    name="quality_grade_id" 
                    id="quality_grade_id" 
                    required 
                    onchange="updateCalculations()"
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                >
                    <option value="" disabled selected>-- Pilih Kondisi Fisik Limbah --</option>
                    @foreach($qualityGrades as $grade)
                        <option 
                            value="{{ $grade->id }}" 
                            data-mult="{{ $grade->point_multiplier }}"
                            {{ old('quality_grade_id') == $grade->id ? 'selected' : '' }}
                        >
                            Grade {{ $grade->grade_code }} - {{ $grade->grade_name }} (Pengali: {{ $grade->point_multiplier }} Poin)
                        </option>
                    @endforeach
                </select>
                <div class="grid grid-cols-2 gap-2 mt-2 text-[11px] text-gray-500">
                    <span class="p-1.5 bg-pink-50/40 rounded border border-pink-100"><strong>A:</strong> Sangat Bagus (Bersih, utuh) = 4x</span>
                    <span class="p-1.5 bg-pink-50/40 rounded border border-pink-100"><strong>B:</strong> Bagus (Cukup bersih) = 3x</span>
                    <span class="p-1.5 bg-pink-50/40 rounded border border-pink-100"><strong>C:</strong> Cukup (Agak kotor) = 2x</span>
                    <span class="p-1.5 bg-pink-50/40 rounded border border-pink-100"><strong>D:</strong> Kurang Layak (Penyok/kotor) = 1x</span>
                </div>
            </div>

            <!-- 3. Kuantitas -->
            <div>
                <label for="quantity" id="quantity_label" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    3. Jumlah Limbah <span class="text-red-500">*</span>
                </label>
                <input 
                    type="number" 
                    name="quantity" 
                    id="quantity" 
                    value="{{ old('quantity', 1) }}" 
                    min="0.1" 
                    step="any"
                    required 
                    oninput="updateCalculations()"
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="Masukkan jumlah..."
                >
            </div>

            <!-- Catatan Tambahan -->
            <div>
                <label for="notes" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                    Catatan / Keterangan Tambahan (Opsional)
                </label>
                <textarea 
                    name="notes" 
                    id="notes" 
                    rows="2" 
                    class="w-full px-4 py-2 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="Contoh: Botol air mineral 600ml sudah dicuci dan dilepas tutupnya..."
                >{{ old('notes') }}</textarea>
            </div>

            <!-- Live Calculation Preview Box -->
            <div class="p-4 rounded-xl bg-[#FFF0F3] border border-pink-200">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-[#BE185D] uppercase block">Perkiraan Poin Didapat:</span>
                        <span id="preview_points" class="text-3xl font-extrabold text-[#BE185D]">0</span>
                        <span class="text-sm font-bold text-[#BE185D]">Poin</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold text-gray-500 uppercase block">Setara Nominal:</span>
                        <span id="preview_rupiah" class="text-xl font-bold text-[#E05780]">Rp 0</span>
                    </div>
                </div>
                <div class="text-[11px] text-[#BE185D] mt-2 pt-2 border-t border-pink-200">
                    *Poin resmi akan masuk ke saldo aktif Anda setelah tim admin memverifikasi fisik limbah saat serah terima.
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-3 px-4 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 transition">
                Kirim Pengajuan Penukaran Limbah
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
    function updateCalculations() {
        const wasteSelect = document.getElementById('waste_type_id');
        const selectedWaste = wasteSelect.options[wasteSelect.selectedIndex];
        
        let unit = 'pcs';
        let base = 1;
        if (selectedWaste && selectedWaste.value) {
            unit = selectedWaste.getAttribute('data-unit') || 'pcs';
            base = parseFloat(selectedWaste.getAttribute('data-base')) || 1;
        }

        const gradeSelect = document.getElementById('quality_grade_id');
        const selectedGrade = gradeSelect.options[gradeSelect.selectedIndex];
        let mult = 1;
        if (selectedGrade && selectedGrade.value) {
            mult = parseFloat(selectedGrade.getAttribute('data-mult')) || 1;
        }

        const qtyInput = document.getElementById('quantity');
        const qtyLabel = document.getElementById('quantity_label');

        if (unit === 'pcs') {
            qtyLabel.innerHTML = '3. Jumlah Botol Plastik (Buah/Pcs) <span class="text-red-500">*</span>';
        } else {
            qtyLabel.innerHTML = '3. Berat Kaleng Aluminium (Gram) <span class="text-red-500">*</span>';
        }

        const qty = parseFloat(qtyInput.value) || 0;

        let points = 0;
        if (qty > 0 && selectedWaste && selectedWaste.value && selectedGrade && selectedGrade.value) {
            points = Math.round((qty / base) * mult);
        }

        let rupiah = 0;
        if (points === 1) rupiah = 100;
        else if (points === 10) rupiah = 500;
        else if (points === 20) rupiah = 1000;
        else if (points > 0) rupiah = points * 50;

        document.getElementById('preview_points').innerText = points.toLocaleString('id-ID');
        document.getElementById('preview_rupiah').innerText = 'Rp ' + rupiah.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', updateCalculations);
</script>
@endpush
@endsection
