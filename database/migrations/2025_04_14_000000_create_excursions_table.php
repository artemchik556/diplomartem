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
        Schema::create('excursions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->string('location', 191);
            $table->string('image', 191)->nullable();
            $table->string('detail_image', 191)->nullable();
            $table->text('transport_car')->nullable();
            $table->text('transport_bus')->nullable();
            $table->text('transport_train')->nullable();
            $table->enum('preparation_level', ['easy', 'medium', 'hard'])->default('medium');
            $table->integer('group_a_seats')->default(0);
            $table->integer('group_b_seats')->default(0);
            $table->integer('group_c_seats')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('guide_id')->nullable()->constrained('guides')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excursions');
    }
}; 