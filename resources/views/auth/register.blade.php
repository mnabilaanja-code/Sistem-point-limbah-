@extends('layouts.app')

@section('title', 'Pendaftaran Akun Peserta')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="card-eco p-8 bg-white border border-pink-100">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] mx-auto flex items-center justify-center mb-3 border border-pink-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#2D232A]">Daftar Akun Baru</h1>
            <p class="text-xs text-[#7E6E78] mt-1">Mulai kumpulkan point bernilai rupiah dari botol & kaleng bekas</p>
        </div>

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Nama Lengkap</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="Contoh: Budi Santoso"
                >
            </div>

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="budi@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi (Minimal 6 Karakter)</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="••••••••"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Ulangi Kata Sandi</label>
                <input 
                    type="password" 
                    name="password_confirmation" 
                    id="password_confirmation" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="••••••••"
                >
            </div>

            <div class="text-xs text-gray-500 leading-relaxed">
                Dengan mendaftar, Anda menyetujui ketentuan pemilahan limbah anorganik/organik yang berlaku di EcoPoints.
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 transition">
                Daftar Sebagai Peserta
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-pink-100 text-center text-xs text-gray-600">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="text-[#E05780] font-semibold hover:underline">Masuk di sini</a>
        </div>
    </div>
</div>
@endsection
