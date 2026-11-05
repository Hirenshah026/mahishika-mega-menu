<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Dot color and "Explore ... hub" link shown in the mega menu
        Schema::table('categories', function (Blueprint $table) {
            $table->string('color', 7)->default('#1270bf')->after('description');
            $table->string('url', 255)->default('#')->after('color');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['color', 'url']);
        });
    }
};
