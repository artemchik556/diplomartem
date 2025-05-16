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
        Schema::table('excursions', function (Blueprint $table) {
            if (!Schema::hasColumn('excursions', 'start_date')) {
                $table->dateTime('start_date')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'end_date')) {
                $table->dateTime('end_date')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'detail_image')) {
                $table->string('detail_image')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'transport_car')) {
                $table->text('transport_car')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'transport_bus')) {
                $table->text('transport_bus')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'transport_train')) {
                $table->text('transport_train')->nullable();
            }
            if (!Schema::hasColumn('excursions', 'preparation_level')) {
                $table->enum('preparation_level', ['easy', 'medium', 'hard'])->default('medium');
            }
            if (!Schema::hasColumn('excursions', 'group_a_seats')) {
                $table->integer('group_a_seats')->default(0);
            }
            if (!Schema::hasColumn('excursions', 'group_b_seats')) {
                $table->integer('group_b_seats')->default(0);
            }
            if (!Schema::hasColumn('excursions', 'group_c_seats')) {
                $table->integer('group_c_seats')->default(0);
            }
            if (!Schema::hasColumn('excursions', 'guide_id')) {
                $table->unsignedBigInteger('guide_id')->nullable()->after('id');
                $table->foreign('guide_id')->references('id')->on('guides')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('excursions', function (Blueprint $table) {
            if (Schema::hasColumn('excursions', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('excursions', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('excursions', 'detail_image')) {
                $table->dropColumn('detail_image');
            }
            if (Schema::hasColumn('excursions', 'transport_car')) {
                $table->dropColumn('transport_car');
            }
            if (Schema::hasColumn('excursions', 'transport_bus')) {
                $table->dropColumn('transport_bus');
            }
            if (Schema::hasColumn('excursions', 'transport_train')) {
                $table->dropColumn('transport_train');
            }
            if (Schema::hasColumn('excursions', 'preparation_level')) {
                $table->dropColumn('preparation_level');
            }
            if (Schema::hasColumn('excursions', 'group_a_seats')) {
                $table->dropColumn('group_a_seats');
            }
            if (Schema::hasColumn('excursions', 'group_b_seats')) {
                $table->dropColumn('group_b_seats');
            }
            if (Schema::hasColumn('excursions', 'group_c_seats')) {
                $table->dropColumn('group_c_seats');
            }
            if (Schema::hasColumn('excursions', 'guide_id')) {
                $table->dropForeign(['guide_id']);
                $table->dropColumn('guide_id');
            }
        });
    }
};
