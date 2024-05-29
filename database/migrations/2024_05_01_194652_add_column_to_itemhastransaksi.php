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
            $table->unsignedInteger('cart_id');
            $table->foreign('cart_id')->references('id')->on('cart');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itemhastransaksi', function (Blueprint $table) {
            //
            $table->dropColumn('cart_id');
        });
    }
};
