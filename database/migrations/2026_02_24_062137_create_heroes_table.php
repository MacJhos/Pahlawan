<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category');
            $table->date('birth_date')->nullable();
            $table->date('death_date')->nullable();
            $table->string('hometown');
            $table->string('image_path');
            $table->text('bio_id');
            $table->text('bio_en')->nullable();
            $table->string('quotes')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
