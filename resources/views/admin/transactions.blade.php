@extends('layouts.app')

@section('title', 'Validasi Transaksi Limbah - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#2D232A]">Validasi Penukaran Limbah</h1>
            <p class="text-xs text-[#7E6E78]">Periksa fisik botol & kaleng yang disetor peserta dan setujui pemberian poin</p>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
        <!-- Status Tabs -->
        <div class="flex flex-wrap gap-2 text-xs font-semibold">
            <a href="{{ route('admin.transactions', ['search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ empty($status) ? 'bg-[#E05780] text-white border-[#E05780]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Semua
            </a>
            <a href="{{ route('admin.transactions', ['status' => 'pending', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'pending' ? 'bg-[#FB7185] text-white border-[#FB7185]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Menunggu Validasi
            </a>
            <a href="{{ route('admin.transactions', ['status' => 'approved', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'approved' ? 'bg-[#E05780] text-white border-[#E05780]' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Disetujui
            </a>
            <a href="{{ route('admin.transactions', ['status' => 'rejected', 'search' => $search]) }}" class="px-3.5 py-1.5 rounded-xl border {{ $status === 'rejected' ? 'bg-red-600 text-white border-red-600' : 'bg-white text-gray-600 border-pink-200 hover:bg-pink-50 hover:text-[#E05780]' }}">
                Ditolak
            </a>
        </div>

        <!-- Search input -->
        <form action="{{ route('admin.transactions') }}" method="GET" class="flex items-center space-x-2">
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
                <a href="{{ route('admin.transactions', ['status' => $status]) }}" class="text-xs text-gray-400 hover:text-gray-600">&times; Reset</a>
            @endif
        </form>
    </div>

    <!-- Transactions Table -->
    <div class="card-eco bg-white overflow-hidden border border-pink-100">
        @if($transactions->isEmpty())
            <div class="text-center py-12 px-4 text-gray-400">
                <p class="text-sm font-medium">Tidak ada transaksi ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-pink-50/40 border-b border-pink-100 text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">ID & Tanggal</th>
                            <th class="py-3.5 px-4">Peserta</th>
                            <th class="py-3.5 px-4">Limbah & Grade</th>
                            <th class="py-3.5 px-4">Jumlah Setoran</th>
                            <th class="py-3.5 px-4">Poin</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4">Catatan</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        @foreach($transactions as $tx)
                            <tr class="hover:bg-pink-50/40 transition">
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-900 block">#{{ $tx->id }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $tx->created_at->format('d M Y, H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-gray-800 block">{{ $tx->user->name }}</span>
                                    <span class="text-gray-400 text-[11px]">{{ $tx->user->email }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-semibold text-gray-800 block">{{ $tx->wasteType->name }}</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-pink-50 text-[#E05780] border border-pink-100 mt-0.5">
                                        Grade {{ $tx->qualityGrade->grade_code }} ({{ $tx->qualityGrade->grade_name }})
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-700">
                                    {{ number_format($tx->quantity) }} {{ $tx->wasteType->unit_type == 'pcs' ? 'Buah' : 'Gram' }}
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-[#E05780] text-sm">
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
                                        <div class="truncate text-[11px]" title="{{ $tx->notes }}">
                                            <span class="font-semibold text-gray-600">Peserta:</span> {{ $tx->notes }}
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
                                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                    @if($tx->status === 'pending')
                                        <div class="inline-flex items-center space-x-1">
                                            <form action="{{ route('admin.transactions.approve', $tx) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg btn-primary text-white font-semibold text-xs shadow-xs transition">
                                                    Setujui
                                                </button>
                                            </form>
                                            <button type="button" onclick="openRejectModal({{ $tx->id }}, '{{ $tx->user->name }}')" class="px-2.5 py-1 rounded-lg border border-red-200 text-red-600 font-semibold text-xs hover:bg-red-50 transition">
                                                Tolak
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

            @if($transactions->hasPages())
                <div class="p-4 border-t border-pink-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

<!-- Modal Reject Transaction -->
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900">Tolak Setoran Limbah</h3>
        <p class="text-xs text-gray-500">
            Penolakan transaksi setoran dari <strong id="rejectUserName"></strong>. Wajib berikan alasan penolakan agar peserta memahami kendalanya.
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
</script>
@endpush
@endsection
