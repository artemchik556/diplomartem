<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Excursion;
use App\Models\ExcursionPhoto;

return new class extends Migration
{
    public function up(): void
    {
        // Перенос данных из полей image и detail_image в таблицу excursion_photos
        $excursions = Excursion::withTrashed()->get(); // Учитываем удаленные записи (soft deletes)

        foreach ($excursions as $excursion) {
            // Перенос основного изображения (image)
            if ($excursion->image) {
                ExcursionPhoto::create([
                    'excursion_id' => $excursion->id,
                    'photo_path' => $excursion->image,
                    'is_preview' => true, // Основное фото становится превью
                ]);
            }

            // Перенос детального изображения (detail_image)
            if ($excursion->detail_image) {
                ExcursionPhoto::create([
                    'excursion_id' => $excursion->id,
                    'photo_path' => $excursion->detail_image,
                    'is_preview' => false,
                ]);
            }
        }

        // Удаление полей image и detail_image
        Schema::table('excursions', function (Blueprint $table) {
            $table->dropColumn(['image', 'detail_image']);
        });
    }

    public function down(): void
    {
        // Восстановление полей image и detail_image
        Schema::table('excursions', function (Blueprint $table) {
            $table->string('image', 191)->nullable()->after('location');
            $table->string('detail_image', 191)->nullable()->after('image');
        });

        // Перенос данных обратно из excursion_photos в excursions
        $photos = ExcursionPhoto::all();

        foreach ($photos as $photo) {
            $excursion = Excursion::withTrashed()->find($photo->excursion_id);
            if ($excursion) {
                if ($photo->is_preview && !$excursion->image) {
                    $excursion->image = $photo->photo_path;
                } elseif (!$excursion->detail_image) {
                    $excursion->detail_image = $photo->photo_path;
                }
                $excursion->save();
            }
        }

        // Удаление таблицы excursion_photos
        Schema::dropIfExists('excursion_photos');
    }
};
