<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('study_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');     // O'zbek, Rus, Ingliz
            $table->string('slug');     // uzbek, russian, english
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_languages');
    }
};
