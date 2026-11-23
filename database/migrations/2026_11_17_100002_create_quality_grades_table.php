<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quality_grades', function (Blueprint $table) {
            $table->id();
            $table->char('grade_code', 1); // 'A', 'B', 'C', 'D'
            $table->string('grade_name'); // 'Sangat Bagus', 'Bagus', 'Cukup', 'Kurang Layak'
            $table->integer('point_multiplier')->default(1); // 4, 3, 2, 1
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_grades');
    }
};
