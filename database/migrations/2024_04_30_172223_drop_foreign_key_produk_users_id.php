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
        //
        Schema::table('produk', function (Blueprint $table) {
            // Hapus constraint foreign key
            $table->dropForeign(['users_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('produk', function (Blueprint $table) {
            // Tambahkan kembali constraint foreign key jika diperlukan
            $table->foreign('users_id')->references('id')->on('users');
        });
    }
};
