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
            $table->string('title');
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->string('image')->nullable();
            $table->string('detail_image')->nullable();
            $table->text('transport_car')->nullable();
            $table->text('transport_bus')->nullable();
            $table->text('transport_train')->nullable();
            $table->text('preparation_level')->nullable();
            $table->integer('group_a_seats')->default(0);
            $table->integer('group_b_seats')->default(0);
            $table->integer('group_c_seats')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('guide_id')->constrained('guides')->onDelete('cascade');
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
