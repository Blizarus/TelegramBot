<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_medicine_id')->constrained('user_medicines')->onDelete('cascade');

            $table->time('time')->comment('Время приёма');
            $table->string('frequency')->default('daily'); // daily, every_X_hours, weekly, custom
            $table->jsonb('days')->nullable()->comment('Для weekly: ["mon", "tue", ...]');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_notified')->nullable();

            $table->timestamps();

            $table->index(['user_medicine_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
