<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('field_schedules', function (Blueprint $table) {
            $table->string('session_name')->after('field_id'); // Menambahkan kolom session_name
        });
    }

    public function down()
    {
        Schema::table('field_schedules', function (Blueprint $table) {
            $table->dropColumn('session_name');
        });
    }
};
