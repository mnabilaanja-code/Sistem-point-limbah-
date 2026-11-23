<?php

namespace App\Http\Controllers;

use App\Models\QualityGrade;
use App\Models\WasteType;
use Illuminate\Http\Request;

class AdminMasterController extends Controller
{
    // ==========================================
    // Waste Types Management
    // ==========================================

    public function wasteTypes()
    {
        $wasteTypes = WasteType::withCount('transactions')->get();
        return view('admin.master.waste-types', compact('wasteTypes'));
    }

    public function storeWasteType(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:waste_types,name'],
            'unit_type' => ['required', 'in:pcs,gram'],
            'base_point_conversion' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama jenis limbah wajib diisi.',
            'name.unique' => 'Jenis limbah sudah ada.',
            'unit_type.required' => 'Satuan (pcs/gram) wajib ditentukan.',
            'base_point_conversion.required' => 'Nilai dasar konversi wajib diisi (misal 1 untuk pcs, 100 untuk gram).',
            'base_point_conversion.min' => 'Nilai dasar konversi minimal 1.',
        ]);

        WasteType::create($validated);

        return back()->with('success', 'Jenis limbah baru berhasil ditambahkan ke data master.');
    }

    public function updateWasteType(Request $request, WasteType $wasteType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:waste_types,name,' . $wasteType->id],
            'unit_type' => ['required', 'in:pcs,gram'],
            'base_point_conversion' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $wasteType->update($validated);

        return back()->with('success', 'Data jenis limbah berhasil diperbarui.');
    }

    public function destroyWasteType(WasteType $wasteType)
    {
        if ($wasteType->transactions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus jenis limbah ini karena telah memiliki riwayat transaksi.');
        }

        $wasteType->delete();

        return back()->with('success', 'Jenis limbah berhasil dihapus.');
    }

    // ==========================================
    // Quality Grades Management
    // ==========================================

    public function qualityGrades()
    {
        $qualityGrades = QualityGrade::withCount('transactions')->orderBy('grade_code')->get();
        return view('admin.master.quality-grades', compact('qualityGrades'));
    }

    public function storeQualityGrade(Request $request)
    {
        $validated = $request->validate([
            'grade_code' => ['required', 'string', 'max:5', 'unique:quality_grades,grade_code'],
            'grade_name' => ['required', 'string', 'max:255'],
            'point_multiplier' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
        ], [
            'grade_code.required' => 'Kode grade wajib diisi (contoh: A, B, C, D).',
            'grade_code.unique' => 'Kode grade sudah terdaftar.',
            'grade_name.required' => 'Nama grade wajib diisi (contoh: Sangat Bagus).',
            'point_multiplier.required' => 'Bobot pengali point wajib diisi.',
            'point_multiplier.min' => 'Bobot pengali minimal 1.',
        ]);

        QualityGrade::create($validated);

        return back()->with('success', 'Grade kualitas baru berhasil ditambahkan.');
    }

    public function updateQualityGrade(Request $request, QualityGrade $qualityGrade)
    {
        $validated = $request->validate([
            'grade_code' => ['required', 'string', 'max:5', 'unique:quality_grades,grade_code,' . $qualityGrade->id],
            'grade_name' => ['required', 'string', 'max:255'],
            'point_multiplier' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $qualityGrade->update($validated);

        return back()->with('success', 'Data grade kualitas berhasil diperbarui.');
    }

    public function destroyQualityGrade(QualityGrade $qualityGrade)
    {
        if ($qualityGrade->transactions()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus grade ini karena telah digunakan dalam riwayat transaksi.');
        }

        $qualityGrade->delete();

        return back()->with('success', 'Grade kualitas berhasil dihapus.');
    }
}
