@extends('layouts.app')

@section('title', 'Kelola Pencairan Saldo - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2D232A]">Manajemen Pencairan Saldo Rupiah</h1>
            <p class="text-xs text-[#7E6E78]">Verifikasi permohonan penarikan poin dan transfer rupiah ke rekening peserta</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-2 text-xs font-semibold">
            <a href="{{ route('admin.redemptions', ['search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ empty($status) ? 'bg-[#E05780] text-white border-[#E05780]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Semua
            </a>
            <a href="{{ route('admin.redemptions', ['status' => 'processing', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'processing' ? 'bg-[#FB7185] text-white border-[#FB7185]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Perlu Ditransfer
            </a>
            <a href="{{ route('admin.redemptions', ['status' => 'completed', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'completed' ? 'bg-[#E05780] text-white border-[#E05780]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Selesai / Sukses
            </a>
            <a href="{{ route('admin.redemptions', ['status' => 'cancelled', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'cancelled' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Dibatalkan
            </a>
        </div>

        <!-- Search input -->
        <form action="{{ route('admin.redemptions') }}" method="GET" class="flex items-center space-x-2">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari nama / email..." 
                class="px-3 py-1.5 rounded-xl border border-pink-200 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-[#E05780]"
            >
            <button type="submit" class="px-3 py-1.5 rounded-xl btn-primary text-xs font-semibold shadow-xs">Cari</button>
            @if($search)
                <a href="{{ route('admin.redemptions', ['status' => $status]) }}" class="text-xs text-gray-400 hover:text-gray-600">&times; Reset</a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden border border-pink-100">
        @if($redemptions->isEmpty())
            <div class="text-center py-12 px-4 text-gray-400">
                <p class="text-sm font-medium">Tidak ada data penarikan saldo.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-pink-50/40 border-b border-pink-100 text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">ID & Tanggal</th>
                            <th class="py-3.5 px-4">Peserta</th>
                            <th class="py-3.5 px-4">Poin Ditukar</th>
                            <th class="py-3.5 px-4">Nominal Transfer</th>
                            <th class="py-3.5 px-4">Tujuan Rekening / E-Wallet</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Info Admin</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        @foreach($redemptions as $rd)
                            <tr class="hover:bg-pink-50/40 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-900 block">#WD-{{ $rd->id }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $rd->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-800 block">{{ $rd->user->name }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $rd->user->email }}</span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-[#BE185D]">
                                    -{{ number_format($rd->points_spent) }} Poin
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-[#E05780] text-sm">
                                    Rp {{ number_format($rd->rupiah_amount, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-800 block">{{ $rd->payment_method }}</span>
                                    <span class="text-gray-600 text-[11px]">{{ $rd->account_details }}</span>
                                    @if($rd->notes)
                                        <div class="text-[10px] text-gray-400 italic">"{{ $rd->notes }}"</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($rd->status === 'completed')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-success">Selesai Ditransfer</span>
                                    @elseif($rd->status === 'processing')
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-warning">Menunggu Transfer</span>
                                    @else
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold badge-danger">Dibatalkan</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 max-w-xs text-gray-500">
                                    @if($rd->admin_notes)
                                        <div class="text-[11px] {{ $rd->status === 'cancelled' ? 'text-red-600 font-medium' : 'text-[#E05780]' }}">
                                            {{ $rd->admin_notes }}
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    @if($rd->status === 'processing')
                                        <div class="inline-flex items-center space-x-1">
                                            <form action="{{ route('admin.redemptions.complete', $rd) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg btn-secondary text-xs font-semibold shadow-xs transition">
                                                    Selesai Transfer
                                                </button>
                                            </form>
                                            <button type="button" onclick="openCancelModal({{ $rd->id }}, '{{ $rd->user->name }}')" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 font-semibold text-xs hover:bg-red-50 transition">
                                                Batal
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-xs italic">Tuntas</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($redemptions->hasPages())
                <div class="p-4 border-t border-pink-100">
                    {{ $redemptions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<!-- Modal Cancel Redemption -->
<div id="cancelModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Batalkan Penarikan Dana</h3>
        <p class="text-xs text-gray-500">
            Pembatalan untuk <strong id="cancelTargetName"></strong>. Poin peserta akan secara otomatis dikembalikan ke saldo aktifnya.
        </p>
        <form id="cancelForm" method="POST" action="">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Alasan Pembatalan <span class="text-red-500">*</span></label>
                <textarea name="admin_notes" required rows="3" class="w-full px-3 py-2 rounded-xl border border-gray-300 text-xs focus:ring-2 focus:ring-red-500" placeholder="Tulis alasan pembatalan (misal nomor rekening tidak cocok / bank gangguan)..."></textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCancelModal()" class="px-4 py-2 rounded-xl border border-gray-300 text-xs text-gray-700 hover:bg-gray-50">Tutup</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-700">Batalkan Penarikan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openCancelModal(rdId, userName) {
        document.getElementById('cancelTargetName').innerText = userName;
        document.getElementById('cancelForm').action = "{{ url('admin/redemptions') }}/" + rdId + "/cancel";
        document.getElementById('cancelModal').classList.remove('hidden');
    }
    function closeCancelModal() {
        document.getElementById('cancelModal').classList.add('hidden');
    }
</script>
@endpush
@endsection
