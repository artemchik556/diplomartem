<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Excursion;
use App\Models\Guide;
use Carbon\Carbon;

class ExcursionSeeder extends Seeder
{
    public function run()
    {
        // Удаляем все экскурсии перед созданием новых
        Excursion::query()->delete();

        // Получаем ID гидов
        $guides = Guide::all();
        if ($guides->isEmpty()) {
            $this->command->error('Нет доступных гидов. Сначала запустите GuideSeeder.');
            return;
        }

        // Создаём экскурсии
        $excursions = [
            [
                'title' => 'Исторический центр города',
                'description' => 'Погрузитесь в историю нашего города через его архитектуру и памятники. Узнайте о важных событиях и выдающихся личностях, которые оставили свой след в истории. Маршрут включает посещение главных достопримечательностей, исторических зданий и музеев.',
                'price' => 1500.00,
                'start_date' => Carbon::now()->addDays(2)->setHour(10)->setMinute(0),
                'end_date' => Carbon::now()->addDays(2)->setHour(13)->setMinute(0),
                'location' => 'Центральная площадь',
                'image' => 'excursions/historical.jpg',
                'detail_image' => 'excursions/historical-detail.jpg',
                'transport_car' => 'Парковка доступна на Центральной площади (бесплатная). Рекомендуется прибыть за 15 минут до начала экскурсии.',
                'transport_bus' => 'Остановка "Центральная площадь", маршруты 1, 2, 3. Интервал движения 10-15 минут.',
                'transport_train' => 'Ближайший вокзал в 15 минутах ходьбы. От вокзала можно добраться на автобусе №5 до остановки "Центральная площадь".',
                'preparation_level' => 'easy',
                'guide_id' => $guides[0]->id,
                'group_a_seats' => 10,
                'group_b_seats' => 15,
                'group_c_seats' => 20
            ],
            [
                'title' => 'Природный заповедник "Зелёный оазис"',
                'description' => 'Исследуйте красоты местного заповедника. Узнайте о местной флоре и фауне, посетите смотровые площадки с панорамными видами. Экскурсия включает прогулку по экологическим тропам, посещение информационного центра и пикник на специально оборудованной площадке.',
                'price' => 2000.00,
                'start_date' => Carbon::now()->addDays(5)->setHour(9)->setMinute(0),
                'end_date' => Carbon::now()->addDays(5)->setHour(13)->setMinute(0),
                'location' => 'Заповедник "Зелёный оазис"',
                'image' => 'excursions/nature.jpg',
                'detail_image' => 'excursions/nature-detail.jpg',
                'transport_car' => 'Парковка у главного входа в заповедник (бесплатная). Рекомендуется прибыть за 20 минут до начала экскурсии.',
                'transport_bus' => 'Специальный маршрут до заповедника отправляется от Центральной площади в 8:30. Стоимость проезда включена в цену экскурсии.',
                'transport_train' => 'Ближайшая станция в 30 минутах езды на автобусе. От станции до заповедника курсирует специальный маршрут.',
                'preparation_level' => 'medium',
                'guide_id' => $guides[1]->id,
                'group_a_seats' => 8,
                'group_b_seats' => 12,
                'group_c_seats' => 15
            ],
            [
                'title' => 'Горный маршрут "Высокий пик"',
                'description' => 'Захватывающий маршрут по горным тропам. Панорамные виды, чистый воздух и незабываемые впечатления. Экскурсия включает восхождение на смотровую площадку, посещение горного озера и обед с видом на горы. Обязательно наличие удобной обуви и теплой одежды.',
                'price' => 3000.00,
                'start_date' => Carbon::now()->addDays(7)->setHour(8)->setMinute(0),
                'end_date' => Carbon::now()->addDays(7)->setHour(14)->setMinute(0),
                'location' => 'Горный массив "Высокий пик"',
                'image' => 'excursions/mountain.jpg',
                'detail_image' => 'excursions/mountain-detail.jpg',
                'transport_car' => 'Парковка у подножия горы (бесплатная). Рекомендуется прибыть за 30 минут до начала экскурсии.',
                'transport_bus' => 'Трансфер от центра города отправляется в 7:00. Стоимость проезда включена в цену экскурсии.',
                'transport_train' => 'Ближайшая станция в 1 часе езды. От станции до горного массива курсирует специальный маршрут.',
                'preparation_level' => 'hard',
                'guide_id' => $guides[2]->id,
                'group_a_seats' => 6,
                'group_b_seats' => 8,
                'group_c_seats' => 10
            ],
            [
                'title' => 'Архитектурные шедевры города',
                'description' => 'Познакомьтесь с архитектурными шедеврами нашего города. Экскурсия включает посещение исторических зданий, церквей и современных архитектурных сооружений. Вы узнаете о стилях архитектуры, выдающихся архитекторах и истории строительства главных достопримечательностей.',
                'price' => 1800.00,
                'start_date' => Carbon::now()->addDays(3)->setHour(11)->setMinute(0),
                'end_date' => Carbon::now()->addDays(3)->setHour(14)->setMinute(0),
                'location' => 'Исторический центр города',
                'image' => 'excursions/architecture.jpg',
                'detail_image' => 'excursions/architecture-detail.jpg',
                'transport_car' => 'Парковка доступна на Центральной площади (платная). Рекомендуется прибыть за 15 минут до начала экскурсии.',
                'transport_bus' => 'Остановка "Исторический центр", маршруты 4, 5, 6. Интервал движения 10-15 минут.',
                'transport_train' => 'Ближайший вокзал в 20 минутах ходьбы. От вокзала можно добраться на автобусе №7 до остановки "Исторический центр".',
                'preparation_level' => 'easy',
                'guide_id' => $guides[3]->id,
                'group_a_seats' => 12,
                'group_b_seats' => 15,
                'group_c_seats' => 20
            ],
            [
                'title' => 'Этнографический тур "Традиции края"',
                'description' => 'Погрузитесь в культуру и традиции нашего края. Экскурсия включает посещение этнографического музея, мастер-классы по народным промыслам и дегустацию традиционных блюд. Вы узнаете о быте, обычаях и праздниках местных жителей.',
                'price' => 2500.00,
                'start_date' => Carbon::now()->addDays(6)->setHour(10)->setMinute(0),
                'end_date' => Carbon::now()->addDays(6)->setHour(15)->setMinute(0),
                'location' => 'Этнографический музей "Народные традиции"',
                'image' => 'excursions/ethnography.jpg',
                'detail_image' => 'excursions/ethnography-detail.jpg',
                'transport_car' => 'Парковка у музея (бесплатная). Рекомендуется прибыть за 15 минут до начала экскурсии.',
                'transport_bus' => 'Остановка "Этнографический музей", маршруты 8, 9. Интервал движения 15-20 минут.',
                'transport_train' => 'Ближайшая станция в 25 минутах езды на автобусе. От станции до музея курсирует специальный маршрут.',
                'preparation_level' => 'medium',
                'guide_id' => $guides[4]->id,
                'group_a_seats' => 10,
                'group_b_seats' => 12,
                'group_c_seats' => 15
            ]
        ];

        foreach ($excursions as $excursion) {
            Excursion::create($excursion);
        }
    }
} 