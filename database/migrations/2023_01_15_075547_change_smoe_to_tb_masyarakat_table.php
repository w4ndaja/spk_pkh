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
        Schema::table('tb_datamasyarakat', function (Blueprint $table) {
            $table->string('hamil')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('tanggungan')->nullable();
            $table->string('penghasilan')->nullable();
            $table->string('dissabilitas')->nullable();
            $table->string('umur')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tb_datamasyarakat', function (Blueprint $table) {
            //
        });
    }
};
