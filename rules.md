# Rules.md - Panduan Batasan Pekerjaan (Job Scope)

1. **Strict Role Boundary (Kepatuhan Jobdesk):**
   - Dilarang keras mengerjakan atau memasukkan modul fitur di luar ruang lingkup sistem penukaran point limbah organik (seperti fitur e-commerce umum, forum diskusi, atau manajemen inventaris gudang non-sampah) kecuali ada instruksi eksplisit tambahan.
2. **Kepatuhan Algoritma Perhitungan:**
   - Logika kalkulasi point untuk Botol dan Kaleng serta perkalian Grade Kualitas (A, B, C, D) harus konsisten mengikuti ketentuan pada PRD.
   - Konversi mata uang point ke Rupiah harus menggunakan rasio baku yang telah ditentukan (Rp 1.000 = 20 Poin, Rp 500 = 10 Poin, Rp 100 = 1 Poin).
3. **Standar Teknologi:**
   - Wajib menggunakan framework Laravel versi 12 dan PHP minimal versi 8.2.
   - Menggunakan blade template atau komponen frontend standar Laravel yang bersih sesuai panduan Design.md.