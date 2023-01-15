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
        Schema::create('pkh_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('masyarakat_id');
            $table->integer('tahap')->unsigned();
            $table->string('bansos');
            $table->string('idsemesta');
            $table->string('no_kel');
            $table->string('nm_prov');
            $table->string('nm_kab');
            $table->string('nm_kec');
            $table->string('nm_kel');
            $table->text('alamat');
            $table->integer('umur');
            $table->integer('tanggungan');
            $table->string('status');
            $table->integer('penghasilan');
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
        Schema::dropIfExists('pkh_records');
    }
};
