<?php

namespace App\Http\Controllers;

use App\Models\PointRedemption;
use App\Models\QualityGrade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalPeserta = User::where('role', 'peserta')->count();
        $pendingTransactionsCount = Transaction::where('status', 'pending')->count();
        $processingRedemptionsCount = PointRedemption::where('status', 'processing')->count();
        $totalApprovedPoints = (int) Transaction::where('status', 'approved')->sum('earned_points');
        $totalDisbursedRupiah = (float) PointRedemption::where('status', 'completed')->sum('rupiah_amount');

        // Ringkasan limbah yang disetujui berdasarkan jenis limbah
        $wasteSummaries = WasteType::withCount(['transactions as total_approved_qty' => function ($query) {
            $query->where('status', 'approved')->select(\DB::raw('COALESCE(SUM(quantity), 0)'));
        }])->get();

        $pendingTransactions = Transaction::with(['user', 'wasteType', 'qualityGrade'])
            ->where('status', 'pending')
            ->latest()
            ->take(6)
            ->get();

        $processingRedemptions = PointRedemption::with('user')
            ->where('status', 'processing')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalPeserta',
            'pendingTransactionsCount',
            'processingRedemptionsCount',
            'totalApprovedPoints',
            'totalDisbursedRupiah',
            'wasteSummaries',
            'pendingTransactions',
            'processingRedemptions'
        ));
    }

    public function transactions(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = Transaction::with(['user', 'wasteType', 'qualityGrade'])->latest();

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(12)->withQueryString();

        return view('admin.transactions', compact('transactions', 'status', 'search'));
    }

    public function approveTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction->update([
            'status' => 'approved',
            'admin_notes' => $request->input('admin_notes', 'Telah divalidasi dan disetujui.'),
        ]);

        return back()->with('success', "Transaksi #{$transaction->id} berhasil DISETUJUI. {$transaction->earned_points} point telah ditambahkan ke saldo peserta.");
    }

    public function rejectTransaction(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ], [
            'admin_notes.required' => 'Wajib memberikan catatan / alasan penolakan limbah.',
        ]);

        $transaction->update([
            'status' => 'rejected',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', "Transaksi #{$transaction->id} telah DITOLAK dengan alasan tercatat.");
    }

    public function redemptions(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('search');

        $query = PointRedemption::with('user')->latest();

        if ($status && in_array($status, ['processing', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $redemptions = $query->paginate(12)->withQueryString();

        return view('admin.redemptions', compact('redemptions', 'status', 'search'));
    }

    public function completeRedemption(Request $request, PointRedemption $redemption)
    {
        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $redemption->update([
            'status' => 'completed',
            'admin_notes' => $request->input('admin_notes', 'Pembayaran telah ditransfer dan selesai.'),
        ]);

        return back()->with('success', "Pencairan Saldo #{$redemption->id} sebesar Rp " . number_format($redemption->rupiah_amount, 0, ',', '.') . " berhasil diselesaikan.");
    }

    public function cancelRedemption(Request $request, PointRedemption $redemption)
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:500'],
        ], [
            'admin_notes.required' => 'Wajib mengisi alasan pembatalan pencairan point.',
        ]);

        $redemption->update([
            'status' => 'cancelled',
            'admin_notes' => $request->input('admin_notes'),
        ]);

        return back()->with('success', "Pencairan #{$redemption->id} DIBATALKAN. Sebanyak {$redemption->points_spent} point dikembalikan ke saldo peserta.");
    }

    public function users(Request $request)
    {
        $search = $request->query('search');

        $query = User::where('role', 'peserta')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users', compact('users', 'search'));
    }
}
