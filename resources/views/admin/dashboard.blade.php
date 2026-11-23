@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Admin Welcome Header -->
    <div class="card-eco p-6 md:p-8 bg-white border-l-4 border-l-[#E05780]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-[#E05780] uppercase tracking-wider">Panel Kontrol Administrator</span>
                <h1 class="text-2xl md:text-3xl font-bold text-[#2D232A]">Dashboard Manajemen Limbah & Poin</h1>
                <p class="text-xs text-[#7E6E78] mt-1">
                    Kelola verifikasi setoran fisik limbah, pencairan saldo rupiah, dan data master sampah
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.transactions', ['status' => 'pending']) }}" class="px-4 py-2 rounded-xl btn-primary text-xs font-semibold shadow-md shadow-pink-200 flex items-center space-x-1.5">
                    <span>Validasi Limbah ({{ $pendingTransactionsCount }})</span>
                </a>
                <a href="{{ route('admin.redemptions', ['status' => 'processing']) }}" class="px-4 py-2 rounded-xl btn-secondary text-xs font-semibold shadow-md shadow-pink-200 flex items-center space-x-1.5">
                    <span>Pencairan ({{ $processingRedemptionsCount }})</span>
                </a>
            </div>
        </div>
    </div>

    <!-- KPI Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Pending Validations -->
        <div class="card-eco p-6 bg-white border {{ $pendingTransactionsCount > 0 ? 'border-pink-300 ring-2 ring-pink-100' : 'border-pink-100' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-[#BE185D] uppercase tracking-wider">Antrean Verifikasi Limbah</span>
                <span class="w-8 h-8 rounded-lg bg-[#FFF0F3] text-[#E05780] flex items-center justify-center font-bold text-xs border border-pink-100">
                    ⏳
                </span>
            </div>
            <div class="text-3xl font-black text-[#2D232A]">
                {{ number_format($pendingTransactionsCount) }}
            </div>
            <div class="text-xs text-[#BE185D] mt-1 font-medium">
                {{ $pendingTransactionsCount > 0 ? 'Perlu tindakan validasi segera' : 'Semua setoran telah diproses' }}
            </div>
        </div>

        <!-- Pending Payouts -->
        <div class="card-eco p-6 bg-white border {{ $processingRedemptionsCount > 0 ? 'border-rose-300 ring-2 ring-rose-100' : 'border-pink-100' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Antrean Pencairan Saldo</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs border border-rose-100">
                    💸
                </span>
            </div>
            <div class="text-3xl font-black text-[#2D232A]">
                {{ number_format($processingRedemptionsCount) }}
            </div>
            <div class="text-xs text-rose-700 mt-1 font-medium">
                Menunggu transfer dana ke peserta
            </div>
        </div>

        <!-- Total Poin Disetujui -->
        <div class="card-eco p-6 bg-white border border-pink-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-[#E05780] uppercase tracking-wider">Total Poin Diberikan</span>
                <span class="w-8 h-8 rounded-lg bg-[#FFF0F3] text-[#E05780] flex items-center justify-center font-bold text-xs border border-pink-100">
                    ★
                </span>
            </div>
            <div class="text-3xl font-black text-[#2D232A]">
                {{ number_format($totalApprovedPoints) }}
            </div>
            <div class="text-xs text-[#7E6E78] mt-1">
                Dari seluruh transaksi limbah disetujui
            </div>
        </div>

        <!-- Total Rupiah Dicairkan -->
        <div class="card-eco p-6 bg-white border border-pink-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Rupiah Dicairkan</span>
                <span class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs">
                    Rp
                </span>
            </div>
            <div class="text-2xl font-black text-[#2D232A]">
                Rp {{ number_format($totalDisbursedRupiah, 0, ',', '.') }}
            </div>
            <div class="text-xs text-[#7E6E78] mt-1">
                Total dana yang telah dibayarkan
            </div>
        </div>

    </div>

    <!-- Waste Types Collection Summary -->
    <div class="card-eco p-6 bg-white border border-pink-100">
        <h3 class="text-base font-bold text-[#2D232A] mb-4 flex items-center">
            <span class="w-2.5 h-2.5 rounded-full bg-[#E05780] mr-2"></span>
            Total Sampah Terkumpul (Telah Tervalidasi)
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($wasteSummaries as $ws)
                <div class="p-4 rounded-xl bg-pink-50/40 border border-pink-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-gray-800 block">{{ $ws->name }}</span>
                        <span class="text-[11px] text-gray-500">Satuan: {{ $ws->unit_type == 'pcs' ? 'Buah / Pcs' : 'Gram' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-[#E05780]">{{ number_format($ws->total_approved_qty) }}</span>
                        <span class="text-xs font-semibold text-gray-600">{{ $ws->unit_type }}</span>
                    </div>
                </div>
            @endforeach
            <div class="p-4 rounded-xl bg-pink-50/40 border border-pink-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-800 block">Total Peserta Aktif</span>
                    <span class="text-[11px] text-gray-500">Pengguna terdaftar</span>
                </div>
                <div class="text-right">
                    <span class="text-xl font-black text-gray-800">{{ number_format($totalPeserta) }}</span>
                    <span class="text-xs font-semibold text-gray-600">Orang</span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Columns: Antrean Validasi Limbah & Antrean Pencairan -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Queue: Validasi Limbah -->
        <div class="card-eco p-6 bg-white border border-pink-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#2D232A]">Antrean Validasi Limbah</h3>
                    <p class="text-xs text-[#7E6E78]">Pengajuan setoran yang menunggu pemeriksaan fisik</p>
                </div>
                <a href="{{ route('admin.transactions', ['status' => 'pending']) }}" class="text-xs font-semibold text-[#E05780] hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            @if($pendingTransactions->isEmpty())
                <div class="text-center py-8 text-gray-400 text-xs">
                    <p class="font-medium">Tidak ada antrean validasi sampah saat ini.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($pendingTransactions as $pt)
                        <div class="p-3.5 rounded-xl border border-pink-100 bg-pink-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <div class="font-bold text-gray-800">
                                    {{ $pt->user->name }}
                                    <span class="text-gray-400 font-normal">({{ $pt->user->email }})</span>
                                </div>
                                <div class="text-gray-600 mt-0.5">
                                    <strong>{{ $pt->wasteType->name }}</strong> - 
                                    Grade {{ $pt->qualityGrade->grade_code }} ({{ $pt->qualityGrade->grade_name }})
                                </div>
                                <div class="text-[11px] text-gray-500 mt-0.5">
                                    Jumlah: <strong>{{ number_format($pt->quantity) }} {{ $pt->wasteType->unit_type }}</strong> &bull; 
                                    Poin: <strong class="text-[#E05780]">+{{ number_format($pt->earned_points) }}</strong>
                                </div>
                                @if($pt->notes)
                                    <div class="text-[11px] text-gray-400 italic mt-0.5">"{{ $pt->notes }}"</div>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2 shrink-0">
                                <form action="{{ route('admin.transactions.approve', $pt) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg btn-primary text-xs font-semibold shadow-xs transition">
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" onclick="openRejectModal({{ $pt->id }}, '{{ $pt->user->name }}')" class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 font-semibold text-xs hover:bg-red-50 transition">
                                    Tolak
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Queue: Pencairan Saldo -->
        <div class="card-eco p-6 bg-white border border-pink-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#2D232A]">Antrean Pencairan Saldo</h3>
                    <p class="text-xs text-[#7E6E78]">Pengajuan penarikan dana rupiah yang perlu ditransfer</p>
                </div>
                <a href="{{ route('admin.redemptions', ['status' => 'processing']) }}" class="text-xs font-semibold text-[#E05780] hover:underline">
                    Kelola Semua &rarr;
                </a>
            </div>

            @if($processingRedemptions->isEmpty())
                <div class="text-center py-8 text-gray-400 text-xs">
                    <p class="font-medium">Tidak ada antrean pencairan dana.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($processingRedemptions as $pr)
                        <div class="p-3.5 rounded-xl border border-pink-100 bg-pink-50/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <div class="font-bold text-gray-800">
                                    {{ $pr->user->name }}
                                    <span class="text-gray-400 font-normal">({{ $pr->user->email }})</span>
                                </div>
                                <div class="text-gray-700 mt-0.5">
                                    Nominal: <strong class="text-emerald-700 font-bold">Rp {{ number_format($pr->rupiah_amount, 0, ',', '.') }}</strong> 
                                    <span class="text-gray-500">({{ $pr->points_spent }} Poin)</span>
                                </div>
                                <div class="text-[11px] text-gray-600 mt-0.5">
                                    {{ $pr->payment_method }}: <strong>{{ $pr->account_details }}</strong>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 shrink-0">
                                <form action="{{ route('admin.redemptions.complete', $pr) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-lg btn-secondary text-xs font-semibold shadow-xs transition">
                                        Selesai Transfer
                                    </button>
                                </form>
                                <button type="button" onclick="openCancelRedemptionModal({{ $pr->id }}, '{{ $pr->user->name }}')" class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 font-semibold text-xs hover:bg-red-50 transition">
                                    Batalkan
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>

<!-- Modal Reject Transaction -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Tolak Setoran Limbah</h3>
        <p class="text-xs text-gray-500">
            Penolakan transaksi setoran dari <strong id="rejectUserName"></strong>. Wajib berikan alasan penolakan agar peserta memahami kendalanya (misal: sampah kotor berat, botol pecah, dll).
        </p>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="admin_notes" required rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-red-500" placeholder="Tuliskan alasan penolakan..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeRejectModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Konfirmasi Tolak</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Cancel Redemption -->
<div id="cancelRedemptionModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Batalkan Pencairan Dana</h3>
        <p class="text-xs text-gray-500">
            Pembatalan pencairan dana untuk <strong id="cancelUserName"></strong>. Poin peserta akan secara otomatis dikembalikan ke saldo aktifnya.
        </p>
        <form id="cancelRedemptionForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Pembatalan <span class="text-red-500">*</span></label>
                <textarea name="admin_notes" required rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-red-500" placeholder="Contoh: Nomor rekening tidak valid / akun bank tidak ditemukan..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCancelRedemptionModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Tutup</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Batalkan Pencairan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openRejectModal(txId, userName) {
        document.getElementById('rejectUserName').innerText = userName;
        document.getElementById('rejectForm').action = "{{ url('admin/transactions') }}/" + txId + "/reject";
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function openCancelRedemptionModal(rdId, userName) {
        document.getElementById('cancelUserName').innerText = userName;
        document.getElementById('cancelRedemptionForm').action = "{{ url('admin/redemptions') }}/" + rdId + "/cancel";
        document.getElementById('cancelRedemptionModal').classList.remove('hidden');
    }
    function closeCancelRedemptionModal() {
        document.getElementById('cancelRedemptionModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
