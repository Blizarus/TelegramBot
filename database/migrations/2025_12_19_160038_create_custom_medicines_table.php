<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_medicines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('trade_name');
            $table->string('inn')->nullable();
            $table->string('dosage_form')->nullable();
            $table->string('dosage')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'trade_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_medicines');
    }
};
