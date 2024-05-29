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
        Schema::table('itemhastransaksi', function (Blueprint $table) {
            //
            $table->unsignedInteger('items_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itemhastransaksi', function (Blueprint $table) {
            //
            $table->dropColumn('items_id');
        });
    }
};
