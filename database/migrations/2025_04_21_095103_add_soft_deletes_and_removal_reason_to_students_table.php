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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('removal_reason_id')
                  ->nullable()
                  ->after('marketing_source_id')
                  ->constrained('removal_reasons')
                  ->onDelete('set null');

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            if (Schema::hasColumn('students', 'removal_reason_id')) {
                 $table->dropForeign(['removal_reason_id']);
                 $table->dropColumn('removal_reason_id');
            }

            $table->dropSoftDeletes();
        });
    }
};
