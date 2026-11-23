<?php

namespace Tests\Feature;

use App\Models\PointRedemption;
use App\Models\QualityGrade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LimbahPointTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $peserta;
    protected WasteType $botol;
    protected WasteType $kaleng;
    protected QualityGrade $gradeA;
    protected QualityGrade $gradeB;
    protected QualityGrade $gradeC;
    protected QualityGrade $gradeD;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup users
        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $this->peserta = User::create([
            'name' => 'Peserta Test',
            'email' => 'peserta@test.com',
            'password' => bcrypt('password123'),
            'role' => 'peserta',
        ]);

        // Setup waste types
        $this->botol = WasteType::create([
            'name' => 'Botol Plastik',
            'unit_type' => 'pcs',
            'base_point_conversion' => 1,
            'description' => '1 Botol = 1 Poin dasar',
        ]);

        $this->kaleng = WasteType::create([
            'name' => 'Kaleng Aluminium',
            'unit_type' => 'gram',
            'base_point_conversion' => 100,
            'description' => '100 gram = 1 Poin dasar',
        ]);

        // Setup quality grades
        $this->gradeA = QualityGrade::create(['grade_code' => 'A', 'grade_name' => 'Sangat Bagus', 'point_multiplier' => 4]);
        $this->gradeB = QualityGrade::create(['grade_code' => 'B', 'grade_name' => 'Bagus', 'point_multiplier' => 3]);
        $this->gradeC = QualityGrade::create(['grade_code' => 'C', 'grade_name' => 'Cukup', 'point_multiplier' => 2]);
        $this->gradeD = QualityGrade::create(['grade_code' => 'D', 'grade_name' => 'Kurang Layak', 'point_multiplier' => 1]);
    }

    /**
     * Test algoritma perhitungan poin limbah sesuai PRD:
     * - Botol: 1 Botol = 1 Poin dasar dikalikan multiplier grade
     * - Kaleng: 100 gram = 1 Poin dasar dikalikan multiplier grade
     */
    public function test_waste_point_calculation_algorithm(): void
    {
        // 10 Botol Grade A (4x) = 40 Poin
        $this->assertEquals(40, Transaction::calculateEarnedPoints($this->botol, $this->gradeA, 10));

        // 10 Botol Grade B (3x) = 30 Poin
        $this->assertEquals(30, Transaction::calculateEarnedPoints($this->botol, $this->gradeB, 10));

        // 10 Botol Grade C (2x) = 20 Poin
        $this->assertEquals(20, Transaction::calculateEarnedPoints($this->botol, $this->gradeC, 10));

        // 10 Botol Grade D (1x) = 10 Poin
        $this->assertEquals(10, Transaction::calculateEarnedPoints($this->botol, $this->gradeD, 10));

        // 500 Gram Kaleng Grade A (4x) = (500 / 100) * 4 = 20 Poin
        $this->assertEquals(20, Transaction::calculateEarnedPoints($this->kaleng, $this->gradeA, 500));

        // 500 Gram Kaleng Grade B (3x) = (500 / 100) * 3 = 15 Poin
        $this->assertEquals(15, Transaction::calculateEarnedPoints($this->kaleng, $this->gradeB, 500));

        // 100 Gram Kaleng Grade D (1x) = (100 / 100) * 1 = 1 Poin
        $this->assertEquals(1, Transaction::calculateEarnedPoints($this->kaleng, $this->gradeD, 100));
    }

    /**
     * Test konversi poin ke rupiah sesuai rasio baku PRD & Rules:
     * - Rp 1.000 = 20 Poin
     * - Rp 500 = 10 Poin
     * - Rp 100 = 1 Poin
     * - Rasio umum: 1 Poin = Rp 50
     */
    public function test_point_to_rupiah_conversion_ratio(): void
    {
        $this->assertEquals(1000.0, PointRedemption::calculateRupiah(20));
        $this->assertEquals(500.0, PointRedemption::calculateRupiah(10));
        $this->assertEquals(100.0, PointRedemption::calculateRupiah(1));
        $this->assertEquals(5000.0, PointRedemption::calculateRupiah(100));
        $this->assertEquals(50000.0, PointRedemption::calculateRupiah(1000));
    }

    /**
     * Test alur pengajuan limbah oleh peserta, persetujuan admin, dan penambahan poin
     */
    public function test_peserta_waste_submission_and_admin_approval_workflow(): void
    {
        // Peserta mengajukan 15 botol grade A (60 poin)
        $response = $this->actingAs($this->peserta)->post(route('peserta.transactions.store'), [
            'waste_type_id' => $this->botol->id,
            'quality_grade_id' => $this->gradeA->id,
            'quantity' => 15,
            'notes' => '15 botol aqua bersih',
        ]);

        $response->assertRedirect(route('peserta.transactions'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->peserta->id,
            'quantity' => 15,
            'earned_points' => 60,
            'status' => 'pending',
        ]);

        // Poin pending belum masuk ke available points
        $this->assertEquals(0, $this->peserta->fresh()->availablePoints());

        // Admin melakukan approve
        $transaction = Transaction::first();
        $adminResp = $this->actingAs($this->admin)->post(route('admin.transactions.approve', $transaction), [
            'admin_notes' => 'Fisik limbah sesuai standar.',
        ]);

        $adminResp->assertSessionHas('success');
        $this->assertEquals('approved', $transaction->fresh()->status);

        // Setelah disetujui, poin masuk ke saldo aktif peserta
        $this->assertEquals(60, $this->peserta->fresh()->availablePoints());
    }

    /**
     * Test penarikan poin ke rupiah oleh peserta dan pencairan oleh admin
     */
    public function test_point_redemption_workflow(): void
    {
        // Beri peserta 60 poin approved terlebih dahulu
        Transaction::create([
            'user_id' => $this->peserta->id,
            'waste_type_id' => $this->botol->id,
            'quality_grade_id' => $this->gradeA->id,
            'quantity' => 15,
            'earned_points' => 60,
            'status' => 'approved',
        ]);

        $this->assertEquals(60, $this->peserta->fresh()->availablePoints());

        // Peserta tarik 20 poin ke GoPay (setara Rp 1.000)
        $resp = $this->actingAs($this->peserta)->post(route('peserta.redemptions.store'), [
            'points_spent' => 20,
            'payment_method' => 'GoPay',
            'account_details' => '081234567890 (Budi)',
            'notes' => 'Pencairan pertama',
        ]);

        $resp->assertRedirect(route('peserta.redemptions'));
        $this->assertDatabaseHas('point_redemptions', [
            'user_id' => $this->peserta->id,
            'points_spent' => 20,
            'rupiah_amount' => 1000.00,
            'status' => 'processing',
        ]);

        // Saldo aktif berkurang menjadi 40 poin
        $this->assertEquals(40, $this->peserta->fresh()->availablePoints());

        // Admin menyelesaikan transfer
        $redemption = PointRedemption::first();
        $this->actingAs($this->admin)->post(route('admin.redemptions.complete', $redemption), [
            'admin_notes' => 'Transfer berhasil via GoPay ref #987654',
        ]);

        $this->assertEquals('completed', $redemption->fresh()->status);
        $this->assertEquals(1000.0, $this->peserta->fresh()->totalRupiahWithdrawn());

        // Peserta mencoba menarik lebih dari saldo aktif (misal 50 poin, padahal sisa 40)
        $failedResp = $this->actingAs($this->peserta)->post(route('peserta.redemptions.store'), [
            'points_spent' => 50,
            'payment_method' => 'Transfer BCA',
            'account_details' => '1234567890 (Budi)',
        ]);

        $failedResp->assertSessionHasErrors('points_spent');
    }

    /**
     * Test role-based access control (RBAC)
     */
    public function test_rbac_access_control(): void
    {
        // Guest tidak bisa akses dashboard
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        // Peserta tidak bisa akses panel admin
        $this->actingAs($this->peserta)->get(route('admin.dashboard'))->assertRedirect(route('dashboard'));

        // Admin tidak bisa akses form setor limbah peserta (diarahkan ke admin dashboard)
        $this->actingAs($this->admin)->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
    }
}
