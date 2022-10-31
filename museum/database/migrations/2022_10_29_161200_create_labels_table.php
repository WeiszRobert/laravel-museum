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
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            //name, display (boolean), color (hex)
            $table->string('name');
            $table->boolean('display');
            $table->string('color', 9);
        });


        Schema::create('item_label', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('label_id');
            $table->unsignedBigInteger('item_id');
            $table->timestamps();

            $table->unique(['label_id', 'item_id']);
            $table->foreign('label_id')->references('id')->on('labels')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
          });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('labels');
        Schema::dropIfExists('item_label');
    }
};
