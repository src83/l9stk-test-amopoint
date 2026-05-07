<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('earthquake_events', static function (Blueprint $table) {
            $table->id();
            $table->string('event_id')->unique();

            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();

            // глубина, магнитуда, rms (метрика качества расчёта (меньше - точнее))
            $table->decimal('depth', 8, 2)->nullable();
            $table->decimal('magnitude', 4, 1)->nullable();
            $table->decimal('rms', 5, 2)->nullable();

            // ML - магнитуда по Рихтеру, но есть и другие шкалы
            $table->string('type', 10)->nullable();

            $table->string('location')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();

            $table->timestampTz('event_moment')->index();
            $table->boolean('is_event_update')->default(false);
            $table->timestampTz('last_update_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('earthquake_events');
    }
};
