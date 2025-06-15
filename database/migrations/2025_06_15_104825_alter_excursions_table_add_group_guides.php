<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Шаг 1: Добавляем новые колонки
        Schema::table('excursions', function (Blueprint $table) {
            $table->foreignId('guide_a_id')->nullable()->constrained('guides')->onDelete('cascade')->after('is_active');
            $table->foreignId('guide_b_id')->nullable()->constrained('guides')->onDelete('cascade')->after('guide_a_id');
            $table->foreignId('guide_c_id')->nullable()->constrained('guides')->onDelete('cascade')->after('guide_b_id');
        });

        // Шаг 2: Переносим данные из guide_id в guide_a_id
        \DB::table('excursions')->update([
            'guide_a_id' => \DB::raw('guide_id')
        ]);

        // Шаг 3: Удаляем старую колонку
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropForeign(['guide_id']);
            $table->dropColumn('guide_id');
        });
    }

    public function down(): void
    {
        // Шаг 1: Восстанавливаем guide_id
        Schema::table('excursions', function (Blueprint $table) {
            $table->foreignId('guide_id')->nullable()->constrained('guides')->onDelete('cascade')->after('is_active');
        });

        // Шаг 2: Переносим данные из guide_a_id в guide_id
        \DB::table('excursions')->update([
            'guide_id' => \DB::raw('guide_a_id')
        ]);

        // Шаг 3: Удаляем новые колонки
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropForeign(['guide_a_id']);
            $table->dropForeign(['guide_b_id']);
            $table->dropForeign(['guide_c_id']);
            $table->dropColumn(['guide_a_id', 'guide_b_id', 'guide_c_id']);
        });
    }
};