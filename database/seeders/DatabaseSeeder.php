<?php

namespace Database\Seeders;

use App\Models\PointRedemption;
use App\Models\QualityGrade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin & Peserta
        $admin = User::firstOrCreate(
            ['email' => 'admin@limbah.test'],
            [
                'name' => 'Administrator Limbah',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );

        $peserta1 = User::firstOrCreate(
            ['email' => 'peserta@limbah.test'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'peserta',
            ]
        );

        $peserta2 = User::firstOrCreate(
            ['email' => 'siti@limbah.test'],
            [
                'name' => 'Siti Nurhaliza',
                'password' => Hash::make('password123'),
                'role' => 'peserta',
            ]
        );

        // 2. Buat Master Data Jenis Sampah (Waste Types)
        $botol = WasteType::firstOrCreate(
            ['name' => 'Botol Plastik'],
            [
                'unit_type' => 'pcs',
                'base_point_conversion' => 1, // 1 Botol = 1 Point dasar
                'description' => 'Botol plastik minuman / wadah PET/HDPE bersih tanpa cairan.',
            ]
        );

        $kaleng = WasteType::firstOrCreate(
            ['name' => 'Kaleng Aluminium'],
            [
                'unit_type' => 'gram',
                'base_point_conversion' => 100, // 100 gram = 1 Point dasar
                'description' => 'Kaleng minuman atau makanan aluminium bersih kering.',
            ]
        );

        // 3. Buat Master Data Kualitas (Quality Grades)
        $gradeA = QualityGrade::firstOrCreate(
            ['grade_code' => 'A'],
            [
                'grade_name' => 'Sangat Bagus',
                'point_multiplier' => 4,
                'description' => 'Kondisi fisik sangat prima, bersih, kering, dan label telah dilepas.',
            ]
        );

        $gradeB = QualityGrade::firstOrCreate(
            ['grade_code' => 'B'],
            [
                'grade_name' => 'Bagus',
                'point_multiplier' => 3,
                'description' => 'Kondisi fisik utuh, relatif bersih dengan sedikit sisa label.',
            ]
        );

        $gradeC = QualityGrade::firstOrCreate(
            ['grade_code' => 'C'],
            [
                'grade_name' => 'Cukup',
                'point_multiplier' => 2,
                'description' => 'Kondisi fisik agak berdebu atau kotor ringan, layak didaur ulang.',
            ]
        );

        $gradeD = QualityGrade::firstOrCreate(
            ['grade_code' => 'D'],
            [
                'grade_name' => 'Kurang Layak',
                'point_multiplier' => 1,
                'description' => 'Kondisi penyok, kotor berat, atau memerlukan perlakuan pembersihan khusus.',
            ]
        );

        // 4. Sample Transaksi Penukaran Limbah
        // Transaksi 1: Budi setor 15 Botol Grade A -> (15 / 1) * 4 = 60 point (approved)
        Transaction::firstOrCreate(
            ['user_id' => $peserta1->id, 'quantity' => 15, 'waste_type_id' => $botol->id],
            [
                'quality_grade_id' => $gradeA->id,
                'earned_points' => Transaction::calculateEarnedPoints($botol, $gradeA, 15),
                'status' => 'approved',
                'notes' => '15 Botol mineral 600ml sudah dicuci bersih.',
                'admin_notes' => 'Barang diterima dalam kondisi sangat prima.',
            ]
        );

        // Transaksi 2: Budi setor 500 gram Kaleng Grade B -> (500 / 100) * 3 = 15 point (approved)
        Transaction::firstOrCreate(
            ['user_id' => $peserta1->id, 'quantity' => 500, 'waste_type_id' => $kaleng->id],
            [
                'quality_grade_id' => $gradeB->id,
                'earned_points' => Transaction::calculateEarnedPoints($kaleng, $gradeB, 500),
                'status' => 'approved',
                'notes' => 'Kaleng minuman soda 500 gram.',
                'admin_notes' => 'Timbangan akurat, disetujui.',
            ]
        );

        // Transaksi 3: Siti setor 20 Botol Grade C -> (20 / 1) * 2 = 40 point (pending)
        Transaction::firstOrCreate(
            ['user_id' => $peserta2->id, 'quantity' => 20, 'waste_type_id' => $botol->id],
            [
                'quality_grade_id' => $gradeC->id,
                'earned_points' => Transaction::calculateEarnedPoints($botol, $gradeC, 20),
                'status' => 'pending',
                'notes' => 'Botol kecap dan saus plastik.',
                'admin_notes' => null,
            ]
        );

        // 5. Sample Penukaran Point ke Rupiah
        // Budi menukar 20 point -> Rp 1.000 (completed)
        PointRedemption::firstOrCreate(
            ['user_id' => $peserta1->id, 'points_spent' => 20],
            [
                'rupiah_amount' => PointRedemption::calculateRupiah(20),
                'status' => 'completed',
                'payment_method' => 'GoPay',
                'account_details' => '081234567890 (Budi Santoso)',
                'notes' => 'Tarik point ke GoPay',
                'admin_notes' => 'Saldo telah ditransfer ke GoPay penerima.',
            ]
        );
    }
}
