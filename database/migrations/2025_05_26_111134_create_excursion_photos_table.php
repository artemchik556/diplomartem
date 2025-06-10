<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('excursion_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('excursion_id')->constrained('excursions')->onDelete('cascade');
            $table->string('photo_path', 191);
            $table->boolean('is_preview')->default(false); // Флаг для главного изображения
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('excursion_photos');
    }
};