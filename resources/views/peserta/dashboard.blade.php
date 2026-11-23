@extends('layouts.app')

@section('title', 'Dashboard Peserta')

@section('content')
<div class="space-y-8">

    <!-- Welcome & Balance Banner -->
    <div class="card-eco p-6 md:p-8 bg-white border-l-4 border-l-[#2E7D32]">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-semibold text-[#2E7D32] uppercase tracking-wider">Dashboard Peserta</span>
                <h1 class="text-2xl md:text-3xl font-bold text-[#212121]">Hai, {{ $user->name }}! 👋</h1>
                <p class="text-xs text-[#757575] mt-1">
                    Terima kasih telah berkontribusi menjaga kelestarian lingkungan dengan memilah sampah.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('peserta.transactions.create') }}" class="px-5 py-2.5 rounded-xl btn-primary text-sm font-semibold shadow-sm flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Setor Limbah Baru</span>
                </a>
                <a href="{{ route('peserta.redemptions.create') }}" class="px-5 py-2.5 rounded-xl btn-secondary text-sm font-semibold shadow-sm flex items-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Tarik Poin ke Rupiah</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Poin Aktif -->
        <div class="card-eco p-6 bg-white border border-amber-100 relative overflow-hidden">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-[#8C6200] uppercase tracking-wider">Poin Aktif</span>
                <span class="w-8 h-8 rounded-lg bg-[#FFF8E1] text-[#FFB300] flex items-center justify-center font-bold text-xs">
                    ★
                </span>
            </div>
            <div class="text-3xl font-black text-[#212121]">
                {{ number_format($availablePoints) }}
            </div>
            <div class="text-xs text-[#757575] mt-1 flex items-center">
                <span>Setara ~ <strong class="text-[#2E7D32]">Rp {{ number_format($estimatedActiveRupiah, 0, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Total Poin Disetujui -->
        <div class="card-eco p-6 bg-white border border-green-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-[#2E7D32] uppercase tracking-wider">Total Poin Diperoleh</span>
                <span class="w-8 h-8 rounded-lg bg-[#E8F5E9] text-[#2E7D32] flex items-center justify-center font-bold text-xs">
                    ✓
                </span>
            </div>
            <div class="text-3xl font-black text-[#212121]">
                {{ number_format($totalApprovedPoints) }}
            </div>
            <div class="text-xs text-[#757575] mt-1">
                Akumulasi seluruh setoran limbah
            </div>
        </div>

        <!-- Total Rupiah Telah Dicairkan -->
        <div class="card-eco p-6 bg-white border border-emerald-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Rupiah Dicairkan</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-xs">
                    Rp
                </span>
            </div>
            <div class="text-3xl font-black text-[#212121]">
                Rp {{ number_format($totalRupiahWithdrawn, 0, ',', '.') }}
            </div>
            <div class="text-xs text-[#757575] mt-1">
                Berhasil ditransfer ke rekening
            </div>
        </div>

        <!-- Status Antrean -->
        <div class="card-eco p-6 bg-white border border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-semibold text-gray-600 uppercase tracking-wider">Dalam Proses</span>
                <span class="w-8 h-8 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs">
                    ⏳
                </span>
            </div>
            <div class="text-2xl font-bold text-[#212121]">
                {{ $pendingTransactionsCount }} <span class="text-xs font-normal text-gray-500">Limbah</span> / 
                {{ $processingRedemptionsCount }} <span class="text-xs font-normal text-gray-500">Pencairan</span>
            </div>
            <div class="text-xs text-[#757575] mt-1">
                Menunggu konfirmasi admin
            </div>
        </div>

    </div>

    <!-- 2 Columns: Recent Transactions & Recent Redemptions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Riwayat Setoran Limbah Terakhir -->
        <div class="card-eco p-6 bg-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#212121]">Setoran Limbah Terakhir</h3>
                    <p class="text-xs text-[#757575]">5 pengajuan penukaran sampah terbaru Anda</p>
                </div>
                <a href="{{ route('peserta.transactions') }}" class="text-xs font-semibold text-[#2E7D32] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentTransactions->isEmpty())
                <div class="text-center py-8 text-gray-400 text-sm">
                    <p>Belum ada riwayat setoran limbah.</p>
                    <a href="{{ route('peserta.transactions.create') }}" class="mt-2 inline-block text-xs text-[#2E7D32] font-semibold underline">
                        Mulai Setor Botol/Kaleng
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 uppercase tracking-wider">
                                <th class="pb-2">Jenis & Kualitas</th>
                                <th class="pb-2">Jumlah</th>
                                <th class="pb-2">Poin</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentTransactions as $tx)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3">
                                        <div class="font-bold text-gray-800">{{ $tx->wasteType->name }}</div>
                                        <div class="text-gray-400">Grade {{ $tx->qualityGrade->grade_code }} ({{ $tx->qualityGrade->grade_name }})</div>
                                    </td>
                                    <td class="py-3 font-semibold text-gray-700">
                                        {{ number_format($tx->quantity) }} {{ $tx->wasteType->unit_type == 'pcs' ? 'Buah' : 'Gram' }}
                                    </td>
                                    <td class="py-3 font-bold text-[#2E7D32]">
                                        +{{ number_format($tx->earned_points) }} Poin
                                    </td>
                                    <td class="py-3">
                                        @if($tx->status === 'approved')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-success">Disetujui</span>
                                        @elseif($tx->status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-warning">Menunggu</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-danger">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Riwayat Penarikan Saldo Terakhir -->
        <div class="card-eco p-6 bg-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-[#212121]">Penarikan Saldo Terakhir</h3>
                    <p class="text-xs text-[#757575]">5 transaksi pencairan poin ke Rupiah terbaru</p>
                </div>
                <a href="{{ route('peserta.redemptions') }}" class="text-xs font-semibold text-[#2E7D32] hover:underline">
                    Lihat Semua &rarr;
                </a>
            </div>

            @if($recentRedemptions->isEmpty())
                <div class="text-center py-8 text-gray-400 text-sm">
                    <p>Belum ada riwayat penarikan poin.</p>
                    @if($availablePoints > 0)
                        <a href="{{ route('peserta.redemptions.create') }}" class="mt-2 inline-block text-xs text-[#FF8F00] font-semibold underline">
                            Tarik Poin Anda Sekarang
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 uppercase tracking-wider">
                                <th class="pb-2">Metode & Rekening</th>
                                <th class="pb-2">Poin Ditarik</th>
                                <th class="pb-2">Nominal</th>
                                <th class="pb-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($recentRedemptions as $rd)
                                <tr class="hover:bg-gray-50/50">
                                    <td class="py-3">
                                        <div class="font-bold text-gray-800">{{ $rd->payment_method }}</div>
                                        <div class="text-gray-400 text-[11px]">{{ $rd->account_details }}</div>
                                    </td>
                                    <td class="py-3 font-semibold text-amber-700">
                                        -{{ number_format($rd->points_spent) }} Poin
                                    </td>
                                    <td class="py-3 font-bold text-emerald-700">
                                        Rp {{ number_format($rd->rupiah_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3">
                                        @if($rd->status === 'completed')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-success">Sukses</span>
                                        @elseif($rd->status === 'processing')
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-warning">Diproses</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold badge-danger">Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

</div>
@endsection
