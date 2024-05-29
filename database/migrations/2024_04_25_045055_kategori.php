<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    var $table = 'kategori';

    public function up(): void
    {
        //
        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
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
