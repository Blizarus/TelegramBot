<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trade_name'); // Торговое название
            $table->string('inn')->nullable(); // Действующее вещество (МНН)
            $table->string('dosage_form')->nullable(); // Форма выпуска
            $table->string('dosage')->nullable(); // Дозировка
            $table->string('pack_size')->nullable(); // Кол-во в упаковке
            $table->string('manufacturer')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->string('atc_code')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index('trade_name');
            $table->index('inn');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
