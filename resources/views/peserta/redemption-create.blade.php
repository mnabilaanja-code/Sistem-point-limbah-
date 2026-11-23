@extends('layouts.app')

@section('title', 'Tarik Poin ke Rupiah')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <a href="{{ route('peserta.redemptions') }}" class="text-xs text-gray-500 hover:text-[#E05780] flex items-center mb-1">
            &larr; Kembali ke Riwayat Penarikan
        </a>
        <h1 class="text-2xl font-bold text-[#2D232A]">Tarik Poin ke Saldo Rupiah</h1>
        <p class="text-xs text-[#7E6E78]">Konversi poin reward Anda menjadi uang tunai langsung ke rekening atau e-wallet</p>
    </div>

    <!-- Active Balance Card -->
    <div class="card-eco p-5 bg-gradient-to-r from-pink-50 to-rose-50 border border-pink-200 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-[#E05780] text-white flex items-center justify-center font-bold text-lg shadow-sm shadow-pink-200">
                ★
            </div>
            <div>
                <span class="text-xs font-semibold text-[#BE185D] uppercase tracking-wider block">Poin Aktif Anda</span>
                <span class="text-2xl font-black text-gray-900">{{ number_format($availablePoints) }} Poin</span>
            </div>
        </div>
        <div class="text-right">
            <span class="text-xs text-pink-700 block">Rasio Baku:</span>
            <span class="text-xs font-bold text-[#BE185D]">1 Poin = Rp 50 (20 Poin = Rp 1.000)</span>
        </div>
    </div>

    @if($availablePoints <= 0)
        <div class="card-eco p-8 bg-white text-center border border-pink-100">
            <div class="w-12 h-12 rounded-full bg-pink-100 text-[#E05780] mx-auto flex items-center justify-center mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Saldo Poin Belum Tersedia</h3>
            <p class="text-xs text-gray-500 max-w-md mx-auto mb-4">
                Anda belum memiliki saldo poin aktif yang disetujui untuk ditukarkan. Silakan setor limbah botol/kaleng Anda terlebih dahulu.
            </p>
            <a href="{{ route('peserta.transactions.create') }}" class="px-5 py-2.5 rounded-xl btn-primary text-sm font-semibold inline-block shadow-md shadow-pink-200">
                + Setor Limbah Sekarang
            </a>
        </div>
    @else
        <!-- Redemption Form -->
        <div class="card-eco p-6 md:p-8 bg-white border border-pink-100">
            <form action="{{ route('peserta.redemptions.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Pilihan Paket Cepat -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">
                        Pilih Paket Cepat (Berdasarkan PRD & Ketentuan):
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        @foreach($standardPackages as $pkg)
                            @if($availablePoints >= $pkg['points'])
                                <button 
                                    type="button" 
                                    onclick="selectPackage({{ $pkg['points'] }})"
                                    class="p-2.5 rounded-xl border border-pink-200 hover:border-[#E05780] hover:bg-[#FFF0F3] text-left transition group"
                                >
                                    <span class="block text-xs font-bold text-gray-800 group-hover:text-[#BE185D]">
                                        Rp {{ number_format($pkg['rupiah'], 0, ',', '.') }}
                                    </span>
                                    <span class="block text-[11px] text-gray-500 group-hover:text-pink-700">
                                        {{ $pkg['points'] }} Poin
                                    </span>
                                </button>
                            @else
                                <div class="p-2.5 rounded-xl border border-dashed border-gray-200 text-left opacity-40 cursor-not-allowed">
                                    <span class="block text-xs font-bold text-gray-400">Rp {{ number_format($pkg['rupiah'], 0, ',', '.') }}</span>
                                    <span class="block text-[11px] text-gray-400">{{ $pkg['points'] }} Poin</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Input Poin Ditarik -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="points_spent" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Atau Masukkan Jumlah Poin Sendiri <span class="text-red-500">*</span>
                        </label>
                        <button type="button" onclick="selectPackage({{ $availablePoints }})" class="text-xs font-semibold text-[#E05780] hover:underline">
                            Tarik Semua ({{ $availablePoints }} Poin)
                        </button>
                    </div>
                    <input 
                        type="number" 
                        name="points_spent" 
                        id="points_spent" 
                        value="{{ old('points_spent', min(20, $availablePoints)) }}" 
                        min="1" 
                        max="{{ $availablePoints }}" 
                        required 
                        oninput="updateRupiahCalc()"
                        class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                    >
                </div>

                <!-- Live Rupiah Amount Result -->
                <div class="p-4 rounded-xl bg-[#FFF0F3] border border-pink-200 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-[#BE185D] uppercase block">Saldo Rupiah Yang Didapatkan:</span>
                        <span id="payout_rupiah" class="text-3xl font-black text-[#BE185D]">Rp 1.000</span>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-[#E05780] text-white flex items-center justify-center font-bold text-sm shadow-sm shadow-pink-200">
                        Rp
                    </div>
                </div>

                <!-- Pilihan Metode Pembayaran / Payout -->
                <div>
                    <label for="payment_method" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Metode Pencairan / Saldo Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="payment_method" 
                        id="payment_method" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                    >
                        <option value="" disabled selected>-- Pilih Bank / E-Wallet --</option>
                        <optgroup label="E-Wallet">
                            <option value="GoPay" {{ old('payment_method') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                            <option value="OVO" {{ old('payment_method') == 'OVO' ? 'selected' : '' }}>OVO</option>
                            <option value="DANA" {{ old('payment_method') == 'DANA' ? 'selected' : '' }}>DANA</option>
                            <option value="ShopeePay" {{ old('payment_method') == 'ShopeePay' ? 'selected' : '' }}>ShopeePay</option>
                        </optgroup>
                        <optgroup label="Transfer Bank">
                            <option value="Transfer BCA" {{ old('payment_method') == 'Transfer BCA' ? 'selected' : '' }}>Bank Central Asia (BCA)</option>
                            <option value="Transfer Mandiri" {{ old('payment_method') == 'Transfer Mandiri' ? 'selected' : '' }}>Bank Mandiri</option>
                            <option value="Transfer BRI" {{ old('payment_method') == 'Transfer BRI' ? 'selected' : '' }}>Bank Rakyat Indonesia (BRI)</option>
                            <option value="Transfer BNI" {{ old('payment_method') == 'Transfer BNI' ? 'selected' : '' }}>Bank Negara Indonesia (BNI)</option>
                        </optgroup>
                        <optgroup label="Lainnya">
                            <option value="Tunai / Cash" {{ old('payment_method') == 'Tunai / Cash' ? 'selected' : '' }}>Tunai / Ambil di Lokasi Bank Sampah</option>
                        </optgroup>
                    </select>
                </div>

                <!-- Detail Nomor Rekening / E-Wallet -->
                <div>
                    <label for="account_details" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Nomor Rekening / No. HP & Atas Nama <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="account_details" 
                        id="account_details" 
                        value="{{ old('account_details') }}" 
                        required 
                        class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                        placeholder="Contoh: 081234567890 (a.n. Budi Santoso) atau No Rekening"
                    >
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label for="notes" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                        Catatan Khusus (Opsional)
                    </label>
                    <textarea 
                        name="notes" 
                        id="notes" 
                        rows="2" 
                        class="w-full px-4 py-2 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] text-sm bg-pink-50/20 focus:bg-white transition"
                        placeholder="Catatan tambahan untuk tim admin transfer..."
                    >{{ old('notes') }}</textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 px-4 rounded-xl btn-primary font-bold text-sm shadow-md shadow-pink-200 transition">
                    Konfirmasi & Ajukan Penarikan Saldo
                </button>
            </form>
        </div>
    @endif

</div>

@push('scripts')
<script>
    function selectPackage(points) {
        document.getElementById('points_spent').value = points;
        updateRupiahCalc();
    }

    function updateRupiahCalc() {
        const points = parseInt(document.getElementById('points_spent').value) || 0;
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

        document.getElementById('payout_rupiah').innerText = 'Rp ' + rupiah.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', updateRupiahCalc);
</script>
@endpush
@endsection
