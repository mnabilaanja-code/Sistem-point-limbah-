@extends('layouts.app')

@section('title', 'Riwayat Setoran Limbah')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#212121]">Riwayat Setoran Limbah</h1>
            <p class="text-xs text-[#757575]">Daftar seluruh pengajuan penukaran sampah botol & kaleng Anda</p>
        </div>
        <div>
            <a href="{{ route('peserta.transactions.create') }}" class="px-5 py-2.5 rounded-xl btn-primary text-sm font-semibold shadow-sm inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Setor Limbah Baru</span>
            </a>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="flex flex-wrap gap-2 text-xs font-semibold">
        <a href="{{ route('peserta.transactions') }}" class="px-3.5 py-1.5 rounded-xl border {{ empty($status) ? 'bg-[#2E7D32] text-white border-[#2E7D32]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Semua (All)
        </a>
        <a href="{{ route('peserta.transactions', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'pending' ? 'bg-[#FFB300] text-gray-900 border-[#FFB300]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Menunggu Verifikasi
        </a>
        <a href="{{ route('peserta.transactions', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'approved' ? 'bg-[#2E7D32] text-white border-[#2E7D32]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Disetujui
        </a>
        <a href="{{ route('peserta.transactions', ['status' => 'rejected']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'rejected' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Ditolak
        </a>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden">
        @if($transactions->isEmpty())
            <div class="text-center py-12 px-4 text-gray-400">
                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-sm font-medium">Tidak ada transaksi ditemukan.</p>
                <a href="{{ route('peserta.transactions.create') }}" class="text-xs text-[#2E7D32] underline font-semibold mt-1 inline-block">
                    Setor limbah sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">ID & Tanggal</th>
                            <th class="py-3.5 px-4">Jenis Limbah</th>
                            <th class="py-3.5 px-4">Grade Kualitas</th>
                            <th class="py-3.5 px-4">Jumlah</th>
                            <th class="py-3.5 px-4">Perolehan Poin</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-900 block">#{{ $tx->id }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $tx->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-800">
                                    {{ $tx->wasteType->name }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-gray-100 text-gray-800">
                                        Grade {{ $tx->qualityGrade->grade_code }}
                                    </span>
                                    <span class="text-gray-400 block text-[11px]">{{ $tx->qualityGrade->grade_name }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-700">
                                    {{ number_format($tx->quantity) }} {{ $tx->wasteType->unit_type == 'pcs' ? 'Buah' : 'Gram' }}
                                </td>
                                <td class="py-3.5 px-4 font-bold text-[#2E7D32] text-sm">
                                    +{{ number_format($tx->earned_points) }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($tx->status === 'approved')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-success">Disetujui</span>
                                    @elseif($tx->status === 'pending')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-warning">Menunggu</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-danger">Ditolak</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs text-gray-500">
                                    @if($tx->notes)
                                        <div class="truncate" title="{{ $tx->notes }}">
                                            <span class="font-medium text-gray-600">Peserta:</span> {{ $tx->notes }}
                                        </div>
                                    @endif
                                    @if($tx->admin_notes)
                                        <div class="text-[11px] {{ $tx->status === 'rejected' ? 'text-red-600 font-medium' : 'text-gray-600' }}">
                                            <span class="font-semibold">Admin:</span> {{ $tx->admin_notes }}
                                        </div>
                                    @endif
                                    @if(!$tx->notes && !$tx->admin_notes)
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
