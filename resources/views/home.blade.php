@extends('layouts.app')

@section('title', 'Beranda - Daur Ulang Limbah Jadi Poin & Rupiah')

@section('content')
<div class="space-y-12">

    <!-- Hero Section -->
    <section class="card-eco p-8 md:p-12 bg-white relative overflow-hidden border-t-4 border-t-[#E05780]">
        <div class="max-w-3xl">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#FFF0F3] text-[#E05780] border border-pink-200 mb-4">
                🌸 Gerakan Bijak Kelola Sampah
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-[#2D232A] tracking-tight leading-tight mb-4">
                Ubah Botol & Kaleng Bekas Jadi <span class="text-[#E05780]">Saldo Rupiah</span>
            </h1>
            <p class="text-base md:text-lg text-[#7E6E78] leading-relaxed mb-6">
                Platform penukaran sampah bernilai ekonomis dengan sentuhan estetis. Kumpulkan botol plastik dan kaleng aluminium, dapatkan poin berdasarkan kondisi fisik limbah, dan konversi menjadi uang tunai langsung ke rekening atau e-wallet Anda.
            </p>

            <div class="flex flex-wrap gap-3">
                @auth
                    @if(Auth::user()->isPeserta())
                        <a href="{{ route('peserta.transactions.create') }}" class="px-6 py-3 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 hover:shadow-lg transition">
                            + Setor Limbah Sekarang
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 rounded-xl border border-pink-200 font-semibold text-sm text-gray-700 hover:bg-pink-50 hover:text-[#E05780] transition">
                            Buka Dashboard Saya
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 hover:shadow-lg transition">
                            Buka Panel Administrator
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="px-6 py-3 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 hover:shadow-lg transition">
                        Daftar Jadi Peserta Gratis
                    </a>
                    <a href="{{ route('login') }}" class="px-6 py-3 rounded-xl border border-pink-200 font-semibold text-sm text-gray-700 hover:bg-pink-50 hover:text-[#E05780] transition">
                        Masuk ke Akun
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Impact Counter Stats -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="card-eco p-6 bg-white flex items-center space-x-4 border-l-4 border-l-[#E05780]">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-[#7E6E78] uppercase tracking-wider block">Total Setoran Diterima</span>
                <span class="text-2xl font-bold text-[#2D232A]">{{ number_format($totalApprovedTransactions) }} Transaksi</span>
            </div>
        </div>

        <div class="card-eco p-6 bg-white flex items-center space-x-4 border-l-4 border-l-[#FB7185]">
            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-[#FB7185] flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-[#7E6E78] uppercase tracking-wider block">Total Poin Diberikan</span>
                <span class="text-2xl font-bold text-[#2D232A]">{{ number_format($totalPointsDistributed) }} Poin</span>
            </div>
        </div>

        <div class="card-eco p-6 bg-white flex items-center space-x-4 border-l-4 border-l-rose-400">
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-[#7E6E78] uppercase tracking-wider block">Total Rupiah Dicairkan</span>
                <span class="text-2xl font-bold text-[#2D232A]">Rp {{ number_format($totalRupiahDistributed, 0, ',', '.') }}</span>
            </div>
        </div>
    </section>

    <!-- Interactive Calculator & Business Rules -->
    <section class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Live Calculator Widget -->
        <div class="lg:col-span-6 card-eco p-6 md:p-8 bg-white border border-pink-100">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-9 h-9 rounded-xl bg-[#FFF0F3] text-[#E05780] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-[#2D232A]">Simulasi Kalkulator Poin</h2>
                    <p class="text-xs text-[#7E6E78]">Hitung estimasi poin & saldo rupiah sebelum setor limbah</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">1. Pilih Jenis Limbah</label>
                    <select id="sim_waste" onchange="calcSimulator()" class="w-full px-4 py-2.5 rounded-xl border border-pink-200 text-sm bg-pink-50/30 focus:bg-white focus:ring-2 focus:ring-[#E05780]">
                        @foreach($wasteTypes as $type)
                            <option value="{{ $type->id }}" data-unit="{{ $type->unit_type }}" data-base="{{ $type->base_point_conversion }}">
                                {{ $type->name }} (Satuan: {{ $type->unit_type == 'pcs' ? 'Buah/Pcs' : 'Gram' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">2. Pilih Kualitas (Grade Kondisi Fisik)</label>
                    <select id="sim_grade" onchange="calcSimulator()" class="w-full px-4 py-2.5 rounded-xl border border-pink-200 text-sm bg-pink-50/30 focus:bg-white focus:ring-2 focus:ring-[#E05780]">
                        @foreach($qualityGrades as $grade)
                            <option value="{{ $grade->id }}" data-mult="{{ $grade->point_multiplier }}">
                                Grade {{ $grade->grade_code }} - {{ $grade->grade_name }} (Pengali: {{ $grade->point_multiplier }} Poin)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label id="sim_qty_label" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">
                        3. Masukkan Jumlah (Buah)
                    </label>
                    <input type="number" id="sim_qty" value="10" min="1" step="any" oninput="calcSimulator()" class="w-full px-4 py-2.5 rounded-xl border border-pink-200 text-sm bg-pink-50/30 focus:bg-white focus:ring-2 focus:ring-[#E05780]">
                </div>

                <!-- Simulation Result Card -->
                <div class="mt-6 p-4 rounded-xl bg-[#FFF0F3] border border-pink-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="text-xs text-[#BE185D] font-semibold uppercase block">Estimasi Perolehan:</span>
                            <span id="sim_result_points" class="text-3xl font-black text-[#BE185D]">40</span>
                            <span class="text-sm font-bold text-[#BE185D]">Poin</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-[#7E6E78] font-semibold uppercase block">Nilai Setara Rupiah:</span>
                            <span id="sim_result_rupiah" class="text-2xl font-black text-[#E05780]">Rp 2.000</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-[#BE185D] mt-2 border-t border-pink-200 pt-2">
                        *Rumus: <code>(Kuantitas / Konversi Dasar) × Bobot Grade</code>. Rasio penukaran rupiah: 1 Poin = Rp 50.
                    </p>
                </div>
            </div>
        </div>

        <!-- Business Rules Accordion / Guide -->
        <div class="lg:col-span-6 space-y-4">
            <div class="card-eco p-6 bg-white">
                <h3 class="text-lg font-bold text-[#2D232A] mb-2 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#E05780] text-white flex items-center justify-center text-xs mr-2">1</span>
                    Ketentuan Nilai Dasar Sampah
                </h3>
                <ul class="space-y-2 text-sm text-gray-600 pl-8">
                    <li class="flex items-start">
                        <span class="text-[#E05780] font-bold mr-2">•</span>
                        <span><strong>Botol Plastik:</strong> <code>1 Botol = 1 Poin Dasar</code> (Dihitung per buah/pcs wadah bersih).</span>
                    </li>
                    <li class="flex items-start">
                        <span class="text-[#E05780] font-bold mr-2">•</span>
                        <span><strong>Kaleng Aluminium:</strong> <code>100 Gram = 1 Poin Dasar</code> (Dihitung berdasarkan timbangan berat gram).</span>
                    </li>
                </ul>
            </div>

            <div class="card-eco p-6 bg-white">
                <h3 class="text-lg font-bold text-[#2D232A] mb-2 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#E05780] text-white flex items-center justify-center text-xs mr-2">2</span>
                    Grade Kualitas Fisik Limbah
                </h3>
                <div class="grid grid-cols-2 gap-3 text-xs pl-8">
                    <div class="p-2.5 rounded-lg bg-pink-50/50 border border-pink-100">
                        <span class="font-bold text-[#E05780] block">Grade A (Sangat Bagus)</span>
                        <span class="text-gray-500 block">Pengali: <strong>4 Poin</strong></span>
                        <span class="text-[11px] text-gray-400">Bersih, label lepas, kering utuh.</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-pink-50/50 border border-pink-100">
                        <span class="font-bold text-[#FB7185] block">Grade B (Bagus)</span>
                        <span class="text-gray-500 block">Pengali: <strong>3 Poin</strong></span>
                        <span class="text-[11px] text-gray-400">Utuh, sedikit sisa label.</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-pink-50/50 border border-pink-100">
                        <span class="font-bold text-amber-600 block">Grade C (Cukup)</span>
                        <span class="text-gray-500 block">Pengali: <strong>2 Poin</strong></span>
                        <span class="text-[11px] text-gray-400">Sedikit kotor, layak daur ulang.</span>
                    </div>
                    <div class="p-2.5 rounded-lg bg-pink-50/50 border border-pink-100">
                        <span class="font-bold text-rose-500 block">Grade D (Kurang Layak)</span>
                        <span class="text-gray-500 block">Pengali: <strong>1 Poin</strong></span>
                        <span class="text-[11px] text-gray-400">Penyok berat/kotor.</span>
                    </div>
                </div>
            </div>

            <div class="card-eco p-6 bg-white">
                <h3 class="text-lg font-bold text-[#2D232A] mb-2 flex items-center">
                    <span class="w-6 h-6 rounded-lg bg-[#E05780] text-white flex items-center justify-center text-xs mr-2">3</span>
                    Rasio Konversi Poin ke Rupiah
                </h3>
                <div class="text-xs text-gray-600 pl-8 space-y-1">
                    <p>Konversi menggunakan rasio baku yang telah ditentukan:</p>
                    <div class="flex flex-wrap gap-2 pt-1">
                        <span class="px-2.5 py-1 bg-pink-50 text-[#BE185D] rounded-lg border border-pink-200 font-semibold">1 Poin = Rp 100</span>
                        <span class="px-2.5 py-1 bg-pink-50 text-[#BE185D] rounded-lg border border-pink-200 font-semibold">10 Poin = Rp 500</span>
                        <span class="px-2.5 py-1 bg-pink-50 text-[#BE185D] rounded-lg border border-pink-200 font-semibold">20 Poin = Rp 1.000</span>
                        <span class="px-2.5 py-1 bg-pink-50 text-[#BE185D] rounded-lg border border-pink-200 font-semibold">100 Poin = Rp 5.000</span>
                    </div>
                    <span class="text-[11px] text-gray-400 block pt-1">Kelipatan reguler dihitung dengan rasio baku 1 Poin = Rp 50.</span>
                </div>
            </div>
        </div>
    </section>

    <!-- How it Works 4 Simple Steps -->
    <section class="card-eco p-8 bg-white">
        <div class="text-center max-w-xl mx-auto mb-8">
            <h2 class="text-2xl font-bold text-[#2D232A]">4 Langkah Mudah Tukar Sampah</h2>
            <p class="text-xs text-[#7E6E78] mt-1">Dukung kelestarian lingkungan dan raih penghasilan tambahan</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center p-4 rounded-xl bg-pink-50/40 border border-pink-100">
                <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] font-bold text-lg flex items-center justify-center mx-auto mb-3 border border-pink-200">
                    1
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Pilah & Bersihkan</h4>
                <p class="text-xs text-gray-500">Pisahkan botol dan kaleng, bersihkan dari sisa cairan atau kotoran.</p>
            </div>

            <div class="text-center p-4 rounded-xl bg-pink-50/40 border border-pink-100">
                <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] font-bold text-lg flex items-center justify-center mx-auto mb-3 border border-pink-200">
                    2
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Input Penukaran</h4>
                <p class="text-xs text-gray-500">Masuk ke aplikasi dan ajukan jumlah sampah yang ingin disetor.</p>
            </div>

            <div class="text-center p-4 rounded-xl bg-pink-50/40 border border-pink-100">
                <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] font-bold text-lg flex items-center justify-center mx-auto mb-3 border border-pink-200">
                    3
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Validasi Admin</h4>
                <p class="text-xs text-gray-500">Tim kami mengecek fisik sampah dan menyetujui penambahan poin ke akun Anda.</p>
            </div>

            <div class="text-center p-4 rounded-xl bg-pink-50/40 border border-pink-100">
                <div class="w-12 h-12 rounded-2xl bg-[#E05780] text-white font-bold text-lg flex items-center justify-center mx-auto mb-3 shadow-sm shadow-pink-200">
                    4
                </div>
                <h4 class="font-bold text-sm text-gray-800 mb-1">Cairkan Rupiah</h4>
                <p class="text-xs text-gray-500">Tarik poin ke saldo Bank (BCA, Mandiri, BRI) atau E-Wallet (GoPay, OVO, Dana).</p>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    function calcSimulator() {
        const wasteSelect = document.getElementById('sim_waste');
        const selectedWaste = wasteSelect.options[wasteSelect.selectedIndex];
        const unitType = selectedWaste.getAttribute('data-unit');
        const baseConversion = parseFloat(selectedWaste.getAttribute('data-base')) || 1;

        const gradeSelect = document.getElementById('sim_grade');
        const selectedGrade = gradeSelect.options[gradeSelect.selectedIndex];
        const multiplier = parseFloat(selectedGrade.getAttribute('data-mult')) || 1;

        const qtyInput = document.getElementById('sim_qty');
        const qtyLabel = document.getElementById('sim_qty_label');

        if (unitType === 'pcs') {
            qtyLabel.innerText = '3. Masukkan Jumlah Botol (Buah/Pcs)';
        } else {
            qtyLabel.innerText = '3. Masukkan Berat Kaleng (Gram)';
        }

        const qty = parseFloat(qtyInput.value) || 0;

        let points = 0;
        if (qty > 0 && baseConversion > 0) {
            points = Math.round((qty / baseConversion) * multiplier);
        }

        // Hitung Rupiah
        let rupiah = 0;
        if (points === 1) {
            rupiah = 100;
        } else if (points === 10) {
            rupiah = 500;
        } else if (points === 20) {
            rupiah = 1000;
        } else if (points > 0) {
            rupiah = points * 50;
        }

        document.getElementById('sim_result_points').innerText = points.toLocaleString('id-ID');
        document.getElementById('sim_result_rupiah').innerText = 'Rp ' + rupiah.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', calcSimulator);
</script>
@endpush
@endsection
