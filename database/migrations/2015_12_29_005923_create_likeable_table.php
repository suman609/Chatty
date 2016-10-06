<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('likeable', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('likeable_id');     // <-- the ID of the likeable item
            $table->string('likeable_type');   // <-- The type of item liked. For example a likeable photo would be under Chatty/Models/Photo
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
        //
        Schema::drop('likeable');
    }
}
