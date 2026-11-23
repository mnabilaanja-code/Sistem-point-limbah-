<?php

namespace App\Http\Controllers;

use App\Models\PointRedemption;
use App\Models\QualityGrade;
use App\Models\Transaction;
use App\Models\WasteType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $availablePoints = $user->availablePoints();
        $totalApprovedPoints = $user->totalApprovedPoints();
        $totalRupiahWithdrawn = $user->totalRupiahWithdrawn();
        $pendingTransactionsCount = $user->transactions()->where('status', 'pending')->count();
        $processingRedemptionsCount = $user->pointRedemptions()->where('status', 'processing')->count();

        // Estimasi nilai rupiah dari point yang masih aktif
        $estimatedActiveRupiah = PointRedemption::calculateRupiah($availablePoints);

        $recentTransactions = $user->transactions()
            ->with(['wasteType', 'qualityGrade'])
            ->latest()
            ->take(5)
            ->get();

        $recentRedemptions = $user->pointRedemptions()
            ->latest()
            ->take(5)
            ->get();

        $wasteTypes = WasteType::all();
        $qualityGrades = QualityGrade::orderBy('grade_code')->get();

        return view('peserta.dashboard', compact(
            'user',
            'availablePoints',
            'totalApprovedPoints',
            'totalRupiahWithdrawn',
            'pendingTransactionsCount',
            'processingRedemptionsCount',
            'estimatedActiveRupiah',
            'recentTransactions',
            'recentRedemptions',
            'wasteTypes',
            'qualityGrades'
        ));
    }

    public function createTransaction()
    {
        $wasteTypes = WasteType::all();
        $qualityGrades = QualityGrade::orderBy('grade_code')->get();

        return view('peserta.transaction-create', compact('wasteTypes', 'qualityGrades'));
    }

    public function storeTransaction(Request $request)
    {
        $validated = $request->validate([
            'waste_type_id' => ['required', 'exists:waste_types,id'],
            'quality_grade_id' => ['required', 'exists:quality_grades,id'],
            'quantity' => ['required', 'numeric', 'min:0.1'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'waste_type_id.required' => 'Pilih jenis limbah terlebih dahulu.',
            'waste_type_id.exists' => 'Jenis limbah tidak valid.',
            'quality_grade_id.required' => 'Pilih grade kualitas limbah.',
            'quality_grade_id.exists' => 'Grade kualitas tidak valid.',
            'quantity.required' => 'Kuantitas/jumlah limbah wajib diisi.',
            'quantity.min' => 'Jumlah limbah minimal 0.1.',
        ]);

        $wasteType = WasteType::findOrFail($validated['waste_type_id']);
        $qualityGrade = QualityGrade::findOrFail($validated['quality_grade_id']);

        $earnedPoints = Transaction::calculateEarnedPoints(
            $wasteType,
            $qualityGrade,
            (float) $validated['quantity']
        );

        Transaction::create([
            'user_id' => Auth::id(),
            'waste_type_id' => $wasteType->id,
            'quality_grade_id' => $qualityGrade->id,
            'quantity' => $validated['quantity'],
            'earned_points' => $earnedPoints,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('peserta.transactions')
            ->with('success', 'Pengajuan penukaran limbah berhasil dikirim! Menunggu verifikasi tim admin.');
    }

    public function transactions(Request $request)
    {
        $status = $request->query('status');

        $query = Auth::user()->transactions()->with(['wasteType', 'qualityGrade'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('peserta.transactions', compact('transactions', 'status'));
    }

    public function createRedemption()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $availablePoints = $user->availablePoints();
        $standardPackages = PointRedemption::standardPackages();

        return view('peserta.redemption-create', compact('availablePoints', 'standardPackages'));
    }

    public function storeRedemption(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $availablePoints = $user->availablePoints();

        $validated = $request->validate([
            'points_spent' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'max:100'],
            'account_details' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'points_spent.required' => 'Tentukan jumlah point yang ingin ditukar.',
            'points_spent.min' => 'Penukaran point minimal 1 point.',
            'payment_method.required' => 'Metode pencairan (Bank/E-Wallet/Cash) wajib dipilih.',
            'account_details.required' => 'Nomor rekening atau nomor e-wallet dan atas nama wajib diisi.',
        ]);

        $pointsSpent = (int) $validated['points_spent'];

        if ($pointsSpent > $availablePoints) {
            return back()->withInput()->withErrors([
                'points_spent' => "Poin aktif Anda tidak mencukupi (Tersedia: {$availablePoints} Poin, Diminta: {$pointsSpent} Poin).",
            ]);
        }

        $rupiahAmount = PointRedemption::calculateRupiah($pointsSpent);

        PointRedemption::create([
            'user_id' => $user->id,
            'points_spent' => $pointsSpent,
            'rupiah_amount' => $rupiahAmount,
            'status' => 'processing',
            'payment_method' => $validated['payment_method'],
            'account_details' => $validated['account_details'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('peserta.redemptions')
            ->with('success', 'Pengajuan penarikan point ke Rupiah berhasil dibuat! Admin akan memproses transfer saldo Anda.');
    }

    public function redemptions(Request $request)
    {
        $status = $request->query('status');

        $query = Auth::user()->pointRedemptions()->latest();

        if ($status && in_array($status, ['processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $redemptions = $query->paginate(10)->withQueryString();

        return view('peserta.redemptions', compact('redemptions', 'status'));
    }
}
