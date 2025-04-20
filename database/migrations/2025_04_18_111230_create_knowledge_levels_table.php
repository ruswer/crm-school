<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('knowledge_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');     // Boshlang'ich, O'rta, va h.k.
            $table->string('slug');     // beginner, intermediate, va h.k.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('knowledge_levels');
    }
};
