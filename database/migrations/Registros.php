<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class Registros extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function(Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('message');
            $table->integer('is_identified');
            $table->string('whistleblower_name');
            $table->date('whistleblower_birth');
            $table->string('created_at');
            $table->integer('deleted');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('registros');
    }

}
