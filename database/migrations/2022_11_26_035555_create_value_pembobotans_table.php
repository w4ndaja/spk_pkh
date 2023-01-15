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
        Schema::create('value_pembobotans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembobotan_id');
            $table->foreignId('x_kriteria_id');
            $table->foreignId('y_kriteria_id');
            $table->float('value');
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
        Schema::dropIfExists('value_pembobotans');
    }
};
