<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Ссылка либо на справочник, либо на кастомное
            $table->foreignId('medicine_id')->nullable()->constrained('medicines')->onDelete('cascade');
            $table->foreignId('custom_medicine_id')->nullable()->constrained('custom_medicines')->onDelete('cascade');

            $table->integer('quantity')->default(0)->comment('Остаток таблеток/единиц');
            $table->integer('total_quantity')->nullable()->comment('Исходное количество в упаковке');
            $table->date('expiration_date')->nullable()->comment('Годен до');
            $table->decimal('purchase_price', 10, 2)->nullable()->comment('Цена покупки');
            $table->string('dosage_per_intake')->nullable()->comment('Доза на один приём, например "1 таблетка"');
            $table->text('notes')->nullable()->comment('Примечания пользователя');

            $table->timestamps();

            // Один из двух ID должен быть заполнен
            $table->unique(['user_id', 'medicine_id']);
            $table->unique(['user_id', 'custom_medicine_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_medicines');
    }
};
