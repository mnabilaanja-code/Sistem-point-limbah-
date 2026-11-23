# ERD (Entity Relationship Diagram) - Sistem Penukaran Limbah

Berikut adalah rancangan relasi antar tabel pada database MySQL:

## 1. Tabel `users`
Menyimpan informasi pengguna aplikasi (Admin & Peserta).
- `id` (PK, BigInt)
- `name` (String)
- `email` (String, Unique)
- `password` (String)
- `role` (Enum: 'admin', 'peserta')
- `timestamps`

## 2. Tabel `waste_types`
Menyimpan jenis sampah yang diterima (Botol, Kaleng).
- `id` (PK, BigInt)
- `name` (String, contoh: "Botol Plastik", "Kaleng Aluminium")
- `unit_type` (Enum: 'pcs', 'gram')
- `base_point_conversion` (Integer, misal: 1 botol = 1 point, atau 100 gram = 1 point)
- `timestamps`

## 3. Tabel `quality_grades`
Menyimpan kategori nilai kualitas sampah.
- `id` (PK, BigInt)
- `grade_code` (Char: 'A', 'B', 'C', 'D')
- `grade_name` (String: 'Sangat Bagus', 'Bagus', 'Cukup', 'Kurang Layak')
- `point_multiplier` atau `point_value` (Integer)
- `timestamps`

## 4. Tabel `transactions`
Mencatat riwayat penukaran limbah oleh peserta.
- `id` (PK, BigInt)
- `user_id` (FK ke `users.id`)
- `waste_type_id` (FK ke `waste_types.id`)
- `quality_grade_id` (FK ke `quality_grades.id`)
- `quantity` (Decimal/Integer, jumlah buah atau gram)
- `earned_points` (Integer, total point yang didapat)
- `status` (Enum: 'pending', 'approved', 'rejected')
- `timestamps`

## 5. Tabel `point_redemptions`
Mencatat penukaran point ke rupiah oleh peserta.
- `id` (PK, BigInt)
- `user_id` (FK ke `users.id`)
- `points_spent` (Integer)
- `rupiah_amount` (Decimal/Integer)
- `status` (Enum: 'processing', 'completed', 'cancelled')
- `timestamps`

---
### Relasi Hubungan (Relationships):
- **User** 1-ke-Banyak (**1-to-Many**) dengan **Transaction**
- **User** 1-ke-Banyak (**1-to-Many**) dengan **PointRedemption**
- **WasteType** 1-ke-Banyak (**1-to-Many**) dengan **Transaction**
- **QualityGrade** 1-ke-Banyak (**1-to-Many**) dengan **Transaction**