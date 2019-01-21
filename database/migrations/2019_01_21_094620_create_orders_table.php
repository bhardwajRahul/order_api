<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('distance');
            $table->enum('status', array('UNASSIGNED', 'ASSIGNED'));
            $table->integer('origin_id')->unsigned();
            $table->integer('destination_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('orders', function(Blueprint $table) {
            $table->foreign('origin_id')
                    ->references('id')
                    ->on('orders_origin')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreign('destination_id')
                    ->references('id')
                    ->on('orders_destination')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('orders');
    }

}
