<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        try {
            Schema::table('bookings', function (Blueprint $table) {
                $table->integer('discount')->default(0)->after('status');
                $table->decimal('discount_amount', 10, 2)->default(0)->after('discount');
                $table->decimal('final_price', 10, 2)->after('discount_amount');
            });
        } catch (\Illuminate\Database\QueryException $e) {
            // Если столбцы уже существуют, логируем предупреждение и продолжаем
            if (str_contains($e->getMessage(), 'Duplicate column name')) {
                \Log::warning('Columns discount, discount_amount, or final_price already exist in bookings table.');
            } else {
                throw $e; // Пробрасываем другие ошибки
            }
        }
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            try {
                $table->dropColumn(['discount', 'discount_amount', 'final_price']);
            } catch (\Illuminate\Database\QueryException $e) {
                // Игнорируем ошибку, если столбцы не существуют
                \Log::warning('Columns discount, discount_amount, or final_price could not be dropped: ' . $e->getMessage());
            }
        });
    }
};