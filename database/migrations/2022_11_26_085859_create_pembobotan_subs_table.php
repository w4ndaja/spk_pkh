<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembobotan_subs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kode');
            $table->float('jumlah');
            $table->foreignId('kriteria_id');
            $table->foreignId('sub_kriteria_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembobotan_subs');
    }
};
