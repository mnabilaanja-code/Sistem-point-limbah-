@extends('layouts.app')

@section('title', 'Masuk ke Akun Anda')

@section('content')
<div class="max-w-md mx-auto my-8">
    <div class="card-eco p-8 bg-white border border-pink-100">
        <div class="text-center mb-6">
            <div class="w-12 h-12 rounded-2xl bg-[#FFF0F3] text-[#E05780] mx-auto flex items-center justify-center mb-3 border border-pink-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#2D232A]">Selamat Datang</h1>
            <p class="text-xs text-[#7E6E78] mt-1">Masuk untuk mengelola dan menukarkan point limbah Anda</p>
        </div>

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Alamat Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="nama@email.com"
                >
            </div>

            <div>
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1">Kata Sandi</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    required 
                    class="w-full px-4 py-2.5 rounded-xl border border-pink-200 focus:outline-none focus:ring-2 focus:ring-[#E05780] focus:border-transparent text-sm bg-pink-50/20 focus:bg-white transition"
                    placeholder="••••••••"
                >
            </div>

            <div class="flex items-center justify-between text-xs">
                <label class="flex items-center space-x-2 cursor-pointer text-gray-600">
                    <input type="checkbox" name="remember" class="rounded text-[#E05780] focus:ring-[#E05780]">
                    <span>Ingat Saya</span>
                </label>
            </div>

            <button type="submit" class="w-full py-2.5 px-4 rounded-xl btn-primary font-semibold text-sm shadow-md shadow-pink-200 transition">
                Masuk ke Sistem
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-pink-100 text-center text-xs text-gray-600">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="text-[#E05780] font-semibold hover:underline">Daftar Akun Baru</a>
        </div>

        <!-- Quick Demo Credentials helper box -->
        <div class="mt-6 p-4 rounded-xl bg-pink-50/40 border border-dashed border-pink-200 text-xs">
            <span class="font-bold text-[#E05780] block mb-1.5 flex items-center">
                <svg class="w-3.5 h-3.5 text-[#E05780] mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/></svg>
                Akun Demo untuk Pengujian:
            </span>
            <div class="space-y-1 text-gray-600">
                <div class="flex justify-between">
                    <span><strong>Admin:</strong> admin@limbah.test</span>
                    <button type="button" onclick="setDemo('admin@limbah.test', 'password123')" class="text-[#E05780] font-bold underline">Gunakan</button>
                </div>
                <div class="flex justify-between">
                    <span><strong>Peserta:</strong> peserta@limbah.test</span>
                    <button type="button" onclick="setDemo('peserta@limbah.test', 'password123')" class="text-[#E05780] font-bold underline">Gunakan</button>
                </div>
                <div class="text-[11px] text-gray-400 mt-1">Password: <code>password123</code></div>
            </div>
        </div>
    </div>
</div>

<script>
    function setDemo(email, pass) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = pass;
    }
</script>
@endsection
