<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    var $table = 'transaksi';


    public function up(): void
    {
        //
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->date('tanggal');
            $table->string('metode');
            $table->double('bayar');
            $table->double('total');
            $table->double('kembalian');
            $table->unsignedInteger('item_transaksi_id');
            $table->unsignedInteger('users_id');

            $table->foreign('item_transaksi_id')->references('id')
                ->on('itemhastransaksi');
            $table->foreign('users_id')->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::drop($this->table);
    }
};
