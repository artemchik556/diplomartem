<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        try {
            Schema::table('consultations', function (Blueprint $table) {
                $table->enum('status', ['new', 'processed', 'completed', 'cancelled'])
                      ->default('new');
                $table->text('admin_notes')->nullable();
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Если столбец уже существует, логируем ошибку и продолжаем
            if (str_contains($e->getMessage(), 'Duplicate column name')) {
                \Log::warning('Columns status or admin_notes already exist in consultations table.');
            } else {
                throw $e; // Пробрасываем другие ошибки
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('consultations', function (Blueprint $table) {
            try {
                $table->dropColumn('status');
                $table->dropColumn('admin_notes');
            } catch (\Illuminate\Database\QueryException $e) {
                // Игнорируем ошибку, если столбцы не существуют
                \Log::warning('Columns status or admin_notes could not be dropped: ' . $e->getMessage());
            }
        });
    }
};