# Product Requirements Document (PRD): Sistem Penukaran Point Limbah Organik

## 1. Overview
Sistem Penukaran Point Limbah Organik adalah platform berbasis web untuk mendaur ulang sampah anorganik/organik tertentu (botol dan kaleng) menjadi point, yang kemudian dapat dikonversi menjadi saldo rupiah oleh peserta.

## 2. Target Pengguna & RBAC (Role Based Access Control)
- **Admin**: 
  - Mengelola data master sampah (kategori, bobot, dan nilai kualitas).
  - Mengelola data point, validasi penukaran limbah dari peserta, dan konversi rupiah.
- **Peserta (User)**: 
  - Melakukan pendaftaran/login.
  - Mengajukan atau mencatatkan penukaran limbah sampah (botol & kaleng).
  - Melihat riwayat perolehan point dan saldo rupiah.

## 3. Aturan Bisnis & Perhitungan (Business Rules)
### Jenis Limbah:
- **Botol**: 
  - Perhitungan dasar: `1 Botol = 1 Point`
- **Kaleng**: 
  - Perhitungan dasar: `100 gram = 1 Point`

### Kategori Kualitas Nilai:
Nilai dasar di atas dikalikan atau dikategorikan berdasarkan kondisi fisik limbah:
- **A. Sangat Bagus** = 4 Point (Pengali/Bonus penyesuaian)
- **B. Bagus** = 3 Point
- **C. Cukup** = 2 Point
- **D. Kurang Layak** = 1 Point

### Konversi Point ke Rupiah:
- **Rp 1.000** = 20 Point
- **Rp 500** = 10 Point
- **Rp 100** = 1 Point
*(Atau setara dengan Rasio: 1 Point = Rp 50)*