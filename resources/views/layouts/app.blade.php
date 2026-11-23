<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Penukaran Point Limbah Organik') - EcoPoints</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        :root {
            --primary: #2E7D32;
            --primary-hover: #1B5E20;
            --secondary: #FFB300;
            --secondary-hover: #FF8F00;
            --bg-light: #F4F6F5;
            --surface: #FFFFFF;
            --text-primary: #212121;
            --text-secondary: #757575;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .card-eco {
            background-color: var(--surface);
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
            border: 1px solid rgba(0, 0, 0, 0.06);
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: #212121;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .btn-secondary:hover {
            background-color: var(--secondary-hover);
        }

        /* Status Badges */
        .badge-success {
            background-color: #E8F5E9;
            color: #1B5E20;
            border: 1px solid #C8E6C9;
        }
        .badge-warning {
            background-color: #FFF8E1;
            color: #B78103;
            border: 1px solid #FFE082;
        }
        .badge-danger {
            background-color: #FFEBEE;
            color: #C62828;
            border: 1px solid #FFCDD2;
        }
    </style>
</head>
<body class="bg-[#F4F6F5] text-[#212121] flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-[#2E7D32] flex items-center justify-center text-white shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-lg text-[#2E7D32] tracking-tight block leading-tight">EcoPoints</span>
                            <span class="text-xs text-[#757575] font-medium block">Penukaran Limbah</span>
                        </div>
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Nav Links -->
                            <div class="hidden md:flex items-center space-x-1 pl-6">
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.transactions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.transactions*') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Validasi Limbah
                                </a>
                                <a href="{{ route('admin.redemptions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.redemptions*') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Pencairan Saldo
                                </a>
                                <div class="relative group">
                                    <button class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 flex items-center space-x-1">
                                        <span>Master Data</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div class="absolute left-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-gray-100 hidden group-hover:block z-50 py-1">
                                        <a href="{{ route('admin.master.waste-types') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Jenis Limbah</a>
                                        <a href="{{ route('admin.master.quality-grades') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Grade Kualitas</a>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users*') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Data Peserta
                                </a>
                            </div>
                        @else
                            <!-- Peserta Nav Links -->
                            <div class="hidden md:flex items-center space-x-1 pl-6">
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('peserta.transactions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('peserta.transactions*') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Riwayat Setoran
                                </a>
                                <a href="{{ route('peserta.redemptions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('peserta.redemptions*') ? 'bg-[#E8F5E9] text-[#2E7D32] font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                                    Riwayat Penarikan
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Right Side Actions / User Profile -->
                <div class="flex items-center space-x-3">
                    @auth
                        @if(Auth::user()->isPeserta())
                            <!-- Quick Point Balance Badge -->
                            <div class="hidden sm:flex items-center bg-[#FFF8E1] border border-[#FFE082] px-3 py-1.5 rounded-full text-xs font-semibold text-[#8C6200]">
                                <svg class="w-4 h-4 mr-1.5 text-[#FFB300]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                                </svg>
                                <span>{{ number_format(Auth::user()->availablePoints()) }} Poin Aktif</span>
                            </div>
                        @endif

                        <!-- User Profile Dropdown/Info -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden sm:block">
                                <span class="text-sm font-semibold text-gray-800 block leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-xs uppercase tracking-wider font-semibold {{ Auth::user()->isAdmin() ? 'text-[#2E7D32]' : 'text-[#757575]' }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-lg text-gray-700 hover:bg-gray-100 transition-colors" title="Keluar">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium rounded-xl btn-primary shadow-sm">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Only auth) -->
        @auth
        <div class="md:hidden border-t border-gray-100 px-4 py-2 bg-gray-50 flex items-center justify-between text-xs font-medium">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-[#2E7D32]">Dashboard</a>
                <a href="{{ route('admin.transactions') }}" class="text-gray-700 hover:text-[#2E7D32]">Validasi</a>
                <a href="{{ route('admin.redemptions') }}" class="text-gray-700 hover:text-[#2E7D32]">Pencairan</a>
                <a href="{{ route('admin.master.waste-types') }}" class="text-gray-700 hover:text-[#2E7D32]">Limbah</a>
                <a href="{{ route('admin.master.quality-grades') }}" class="text-gray-700 hover:text-[#2E7D32]">Kualitas</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-[#2E7D32]">Dashboard</a>
                <a href="{{ route('peserta.transactions.create') }}" class="text-[#2E7D32] font-bold">+ Setor</a>
                <a href="{{ route('peserta.redemptions.create') }}" class="text-[#FF8F00] font-bold">Tarik Saldo</a>
                <a href="{{ route('peserta.transactions') }}" class="text-gray-700 hover:text-[#2E7D32]">Riwayat</a>
            @endif
        </div>
        @endauth
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-[#E8F5E9] border border-[#C8E6C9] text-[#1B5E20] flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#2E7D32] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-[#FFEBEE] border border-[#FFCDD2] text-[#C62828] flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#C62828] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-[#FFEBEE] border border-[#FFCDD2] text-[#C62828] shadow-sm">
                <div class="flex items-center space-x-2 mb-1">
                    <svg class="w-5 h-5 text-[#C62828] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-bold">Harap periksa kesalahan berikut:</span>
                </div>
                <ul class="list-disc list-inside text-xs space-y-0.5 ml-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Eco-Friendly Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-[#757575]">
            <div class="flex items-center justify-center space-x-2 mb-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#2E7D32]"></span>
                <span class="font-semibold text-gray-700">Sistem Penukaran Point Limbah Organik &copy; {{ date('Y') }}</span>
            </div>
            <p>Daur ulang sampah botol & kaleng bernilai ekonomis. Bersih lingkungannya, untung rupiahnya.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
