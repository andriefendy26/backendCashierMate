<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */

    var $table = 'itemhastransaksi';

    public function up(): void
    {
        //
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->double('total');
            $table->integer('qty');
            $table->double('harga');
            $table->unsignedInteger('produk_id');


            $table->foreign('produk_id')->references('id')->on('produk');
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
