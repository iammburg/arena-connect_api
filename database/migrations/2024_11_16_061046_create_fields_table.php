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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('field_centre_id')
                ->nullable()
                ->constrained('field_centres')
                ->cascadeOnUpdate();
            $table->enum('type', ['Futsal', 'Badminton']);
            $table->longText("descriptions");
            $table->boolean('status')->default(true); // Changed to boolean, true = available, false = unavailable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
