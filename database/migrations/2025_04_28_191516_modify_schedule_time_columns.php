<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mavjud ma'lumotlarni oldin HH:mm formatiga o'tkazamiz
        $schedules = DB::table('schedules')->get();
        foreach ($schedules as $schedule) {
            DB::table('schedules')
                ->where('id', $schedule->id)
                ->update([
                    'start_time' => substr($schedule->start_time, 0, 5),
                    'end_time' => substr($schedule->end_time, 0, 5)
                ]);
        }

        // Keyin ustun turini o'zgartiramiz
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('start_time', 5)->change();
            $table->string('end_time', 5)->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->time('start_time')->change();
            $table->time('end_time')->change();
        });
    }
};