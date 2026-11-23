@extends('layouts.app')

@section('title', 'Riwayat Penarikan Rupiah')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#212121]">Riwayat Penarikan Saldo</h1>
            <p class="text-xs text-[#757575]">Daftar pencairan poin ke saldo Rupiah Anda</p>
        </div>
        <div>
            <a href="{{ route('peserta.redemptions.create') }}" class="px-5 py-2.5 rounded-xl btn-secondary text-sm font-semibold shadow-sm inline-flex items-center space-x-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>+ Tarik Poin ke Rupiah</span>
            </a>
        </div>
    </div>

    <!-- Status Filters -->
    <div class="flex flex-wrap gap-2 text-xs font-semibold">
        <a href="{{ route('peserta.redemptions') }}" class="px-3.5 py-1.5 rounded-xl border {{ empty($status) ? 'bg-[#2E7D32] text-white border-[#2E7D32]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Semua (All)
        </a>
        <a href="{{ route('peserta.redemptions', ['status' => 'processing']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'processing' ? 'bg-[#FFB300] text-gray-900 border-[#FFB300]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Sedang Diproses
        </a>
        <a href="{{ route('peserta.redemptions', ['status' => 'completed']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'completed' ? 'bg-[#2E7D32] text-white border-[#2E7D32]' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Berhasil Ditransfer
        </a>
        <a href="{{ route('peserta.redemptions', ['status' => 'cancelled']) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'cancelled' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50' }}">
            Dibatalkan
        </a>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden">
        @if($redemptions->isEmpty())
            <div class="text-center py-12 px-4 text-gray-400">
                <div class="w-12 h-12 rounded-full bg-gray-100 text-gray-400 mx-auto flex items-center justify-center mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <p class="text-sm font-medium">Belum ada data penarikan saldo.</p>
                <a href="{{ route('peserta.redemptions.create') }}" class="text-xs text-[#FF8F00] underline font-semibold mt-1 inline-block">
                    Tarik saldo poin sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">ID & Tanggal</th>
                            <th class="py-3.5 px-4">Poin Ditarik</th>
                            <th class="py-3.5 px-4">Jumlah Rupiah</th>
                            <th class="py-3.5 px-4">Tujuan / Rekening</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Catatan / Bukti</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($redemptions as $rd)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-900 block">#WD-{{ $rd->id }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $rd->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-amber-700">
                                    -{{ number_format($rd->points_spent) }} Poin
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-[#2E7D32] text-sm">
                                    Rp {{ number_format($rd->rupiah_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-gray-800 block">{{ $rd->payment_method }}</span>
                                    <span class="text-gray-500 text-[11px]">{{ $rd->account_details }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($rd->status === 'completed')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-success">Selesai / Ditransfer</span>
                                    @elseif($rd->status === 'processing')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-warning">Sedang Diproses</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs text-gray-500">
                                    @if($rd->admin_notes)
                                        <div class="text-[11px] {{ $rd->status === 'cancelled' ? 'text-red-600 font-medium' : 'text-[#2E7D32]' }}">
                                            <span class="font-semibold">Info Admin:</span> {{ $rd->admin_notes }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($redemptions->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $redemptions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
