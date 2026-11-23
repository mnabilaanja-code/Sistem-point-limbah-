@extends('layouts.app')

@section('title', 'Data Peserta - Admin')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#212121]">Data Peserta Penukaran Limbah</h1>
            <p class="text-xs text-[#757575]">Daftar seluruh akun peserta terdaftar, saldo poin, dan total rupiah yang telah dicairkan</p>
        </div>

        <form action="{{ route('admin.users') }}" method="GET" class="flex items-center space-x-2">
            <input 
                type="text" 
                name="search" 
                value="{{ $search }}" 
                placeholder="Cari peserta..." 
                class="px-3 py-1.5 rounded-xl border border-gray-300 text-xs bg-white focus:outline-none focus:ring-2 focus:ring-[#2E7D32]"
            >
            <button type="submit" class="px-3 py-1.5 rounded-xl btn-primary text-xs font-semibold">Cari</button>
            @if($search)
                <a href="{{ route('admin.users') }}" class="text-xs text-gray-400 hover:text-gray-600">&times; Reset</a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="card-eco bg-white overflow-hidden">
        @if($users->isEmpty())
            <div class="text-center py-12 px-4 text-gray-400">
                <p class="text-sm font-medium">Tidak ada peserta ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="py-3.5 px-4">Nama Peserta</th>
                            <th class="py-3.5 px-4">Alamat Email</th>
                            <th class="py-3.5 px-4">Bergabung</th>
                            <th class="py-3.5 px-4">Total Setoran</th>
                            <th class="py-3.5 px-4">Total Poin Disetujui</th>
                            <th class="py-3.5 px-4">Poin Aktif</th>
                            <th class="py-3.5 px-4">Rupiah Telah Dicairkan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($users as $u)
                            <tr class="hover:bg-gray-50/60 transition">
                                <td class="py-3.5 px-4 font-bold text-gray-900">
                                    {{ $u->name }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-600">
                                    {{ $u->email }}
                                </td>
                                <td class="py-3.5 px-4 text-gray-400 text-[11px]">
                                    {{ $u->created_at->format('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-gray-700">
                                    {{ $u->transactions()->count() }} Kali
                                </td>
                                <td class="py-3.5 px-4 font-bold text-[#2E7D32]">
                                    {{ number_format($u->totalApprovedPoints()) }} Poin
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200">
                                        {{ number_format($u->availablePoints()) }} Poin
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-extrabold text-emerald-700">
                                    Rp {{ number_format($u->totalRupiahWithdrawn(), 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
