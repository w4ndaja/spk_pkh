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
        Schema::create('pembobotan_alternatifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_kriteria_id');
            $table->foreignId('masyarakat_id');
            $table->double('l');
            $table->double('m');
            $table->double('u');
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
        Schema::dropIfExists('pembobotan_alternatifs');
    }
};
