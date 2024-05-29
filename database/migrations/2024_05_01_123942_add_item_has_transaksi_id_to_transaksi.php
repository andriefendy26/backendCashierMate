<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
            $table->unsignedInteger('itemhastransaksi_id');
            $table->foreign('itemhastransaksi_id')->references('id')->on('itemhastransaksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            //
            $table->dropColumn('itemhastransaksi_id');
        });
    }
};
