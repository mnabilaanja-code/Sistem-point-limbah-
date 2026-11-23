<?php

namespace App\Http\Controllers;

use App\Models\QualityGrade;
use App\Models\PointRedemption;
use App\Models\Transaction;
use App\Models\WasteType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $wasteTypes = WasteType::all();
        $qualityGrades = QualityGrade::orderBy('grade_code')->get();
        $standardPackages = PointRedemption::standardPackages();

        // Statistik agregat publik
        $totalApprovedTransactions = Transaction::where('status', 'approved')->count();
        $totalPointsDistributed = (int) Transaction::where('status', 'approved')->sum('earned_points');
        $totalRupiahDistributed = (float) PointRedemption::where('status', 'completed')->sum('rupiah_amount');

        return view('home', compact(
            'wasteTypes',
            'qualityGrades',
            'standardPackages',
            'totalApprovedTransactions',
            'totalPointsDistributed',
            'totalRupiahDistributed'
        ));
    }
}
