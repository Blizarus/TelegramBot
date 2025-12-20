<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // Отключить проверку внешних ключей
        Schema::disableForeignKeyConstraints();

        // Очистить таблицу перед заполнением
        DB::table('medicines')->truncate();

        // Включить проверку внешних ключей обратно
        Schema::enableForeignKeyConstraints();

        // Создать фабрику
        $factory = Medicine::factory();

        // Получить список лекарств из фабрики
        $medicines = $factory->createMedicines(50);

        // Вставить в базу данных
        foreach ($medicines as $medicineData) {
            Medicine::create($medicineData);
        }

        $this->command->info('Успешно создано ' . count($medicines) . ' лекарств в базе данных.');

        // Создать дополнительные тестовые данные
        $this->createPopularMedicines();
    }

    protected function createPopularMedicines(): void
    {
        $popularMedicines = [
            'Парацетамол',
            'Аспирин',
            'Нурофен',
            'Цитрамон',
            'Анальгин'
        ];

        foreach ($popularMedicines as $medicineName) {
            // Проверяем, не существует ли уже это лекарство
            if (!Medicine::where('trade_name', $medicineName)->exists()) {
                Medicine::factory()->withMedicine($medicineName)->create();
            }
        }

        $this->command->info('Созданы популярные лекарства: ' . implode(', ', $popularMedicines));
    }
}
