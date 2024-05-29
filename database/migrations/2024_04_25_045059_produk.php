<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    var $table = 'produk';

    public function up(): void
    {
        //
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 50);
            $table->double('harga');
            $table->string('gambar')->nullable();
            $table->integer('qty');
            $table->unsignedInteger('kategori_id');
            $table->unsignedInteger('users_id');

            $table->foreign('kategori_id')->references('id')
                ->on('kategori');
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
