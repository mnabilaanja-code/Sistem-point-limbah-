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
            --primary: #E05780;
            --primary-hover: #C7436B;
            --secondary: #FB7185;
            --secondary-hover: #E11D48;
            --bg-light: #FFF5F7;
            --surface: #FFFFFF;
            --text-primary: #2D232A;
            --text-secondary: #7E6E78;
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
            box-shadow: 0 4px 15px rgba(224, 87, 128, 0.05);
            border: 1px solid rgba(224, 87, 128, 0.12);
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
            color: #ffffff;
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
<body class="bg-[#FFF5F7] text-[#2D232A] flex flex-col min-h-screen">

    <!-- Top Navigation Bar -->
    <nav class="bg-white border-b border-pink-100 sticky top-0 z-40 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <div class="w-10 h-10 rounded-xl bg-[#E05780] flex items-center justify-center text-white shadow-sm shadow-pink-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-lg text-[#E05780] tracking-tight block leading-tight">EcoPoints</span>
                            <span class="text-xs text-[#7E6E78] font-medium block">Penukaran Limbah</span>
                        </div>
                    </a>

                    @auth
                        @if(Auth::user()->isAdmin())
                            <!-- Admin Nav Links -->
                            <div class="hidden md:flex items-center space-x-1 pl-6">
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('admin.transactions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.transactions*') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Validasi Limbah
                                </a>
                                <a href="{{ route('admin.redemptions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.redemptions*') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Pencairan Saldo
                                </a>
                                <div class="relative group">
                                    <button class="px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:text-[#E05780] hover:bg-pink-50 flex items-center space-x-1">
                                        <span>Master Data</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div class="absolute left-0 mt-1 w-48 bg-white rounded-xl shadow-lg border border-pink-100 hidden group-hover:block z-50 py-1">
                                        <a href="{{ route('admin.master.waste-types') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-[#E05780]">Jenis Limbah</a>
                                        <a href="{{ route('admin.master.quality-grades') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-[#E05780]">Grade Kualitas</a>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users*') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Data Peserta
                                </a>
                            </div>
                        @else
                            <!-- Peserta Nav Links -->
                            <div class="hidden md:flex items-center space-x-1 pl-6">
                                <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Dashboard
                                </a>
                                <a href="{{ route('peserta.transactions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('peserta.transactions*') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
                                    Riwayat Setoran
                                </a>
                                <a href="{{ route('peserta.redemptions') }}" class="px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('peserta.redemptions*') ? 'bg-[#FFF0F3] text-[#E05780] font-semibold' : 'text-gray-600 hover:text-[#E05780] hover:bg-pink-50' }}">
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
                            <div class="hidden sm:flex items-center bg-[#FFF0F3] border border-pink-200 px-3 py-1.5 rounded-full text-xs font-semibold text-[#BE185D]">
                                <svg class="w-4 h-4 mr-1.5 text-[#FB7185]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm1 11H9v-2h2v2zm0-4H9V5h2v4z"/>
                                </svg>
                                <span>{{ number_format(Auth::user()->availablePoints()) }} Poin Aktif</span>
                            </div>
                        @endif

                        <!-- User Profile Dropdown/Info -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right hidden sm:block">
                                <span class="text-sm font-semibold text-gray-800 block leading-tight">{{ Auth::user()->name }}</span>
                                <span class="text-xs uppercase tracking-wider font-semibold {{ Auth::user()->isAdmin() ? 'text-[#E05780]' : 'text-[#7E6E78]' }}">
                                    {{ Auth::user()->role }}
                                </span>
                            </div>

                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 border border-pink-200 text-xs font-medium rounded-lg text-gray-700 hover:bg-pink-50 hover:text-[#E05780] transition-colors" title="Keluar">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-[#E05780]">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium rounded-xl btn-primary shadow-sm shadow-pink-200">
                            Daftar Sekarang
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Mobile Menu (Only auth) -->
        @auth
        <div class="md:hidden border-t border-pink-100 px-4 py-2 bg-pink-50/50 flex items-center justify-between text-xs font-medium">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-[#E05780]">Dashboard</a>
                <a href="{{ route('admin.transactions') }}" class="text-gray-700 hover:text-[#E05780]">Validasi</a>
                <a href="{{ route('admin.redemptions') }}" class="text-gray-700 hover:text-[#E05780]">Pencairan</a>
                <a href="{{ route('admin.master.waste-types') }}" class="text-gray-700 hover:text-[#E05780]">Limbah</a>
                <a href="{{ route('admin.master.quality-grades') }}" class="text-gray-700 hover:text-[#E05780]">Kualitas</a>
            @else
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-[#E05780]">Dashboard</a>
                <a href="{{ route('peserta.transactions.create') }}" class="text-[#E05780] font-bold">+ Setor</a>
                <a href="{{ route('peserta.redemptions.create') }}" class="text-[#FB7185] font-bold">Tarik Saldo</a>
                <a href="{{ route('peserta.transactions') }}" class="text-gray-700 hover:text-[#E05780]">Riwayat</a>
            @endif
        </div>
        @endauth
    </nav>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Alerts -->
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-pink-50 border border-pink-200 text-[#BE185D] flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5 text-[#E05780] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-[#FFEBEE] border border-[#FFCDD2] text-[#C62828] flex items-center justify-between shadow-xs">
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
            <div class="mb-6 p-4 rounded-xl bg-[#FFEBEE] border border-[#FFCDD2] text-[#C62828] shadow-xs">
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

    <!-- Soft Pink Aesthetic Footer -->
    <footer class="bg-white border-t border-pink-100 mt-auto py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-[#7E6E78]">
            <div class="flex items-center justify-center space-x-2 mb-2">
                <span class="inline-block w-2.5 h-2.5 rounded-full bg-[#E05780]"></span>
                <span class="font-semibold text-gray-700">Sistem Penukaran Point Limbah Organik &copy; {{ date('Y') }}</span>
            </div>
            <p>Daur ulang sampah botol & kaleng bernilai ekonomis. Bersih lingkungannya, untung rupiahnya.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
