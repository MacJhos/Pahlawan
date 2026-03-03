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

            $table->date('birth_date')->nullable();
            $table->string('hometown');
            $table->string('category')->default('National Hero');
            $table->date('death_date')->nullable();
            $table->string('image_path')->nullable();

            $table->text('quotes')->nullable();

            $table->text('bio_id');
            $table->text('bio_en')->nullable();

            $table->timestamps();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
