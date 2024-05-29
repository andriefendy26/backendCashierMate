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
        Schema::table('itemhastransaksi', function (Blueprint $table) {
            //
            $table->unsignedInteger('produk_id');
            $table->foreign('produk_id')->references('id')->on('produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itemhastransaksi', function (Blueprint $table) {
            //
            $table->dropColumn('produk_id');
        });
    }
};
