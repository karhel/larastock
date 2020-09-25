<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->unsignedDouble('unit_price');
            $table->timestamps();
            
            $table->unique(['order_id', 'product_id']);

            // Foreign key(s)
            $table->foreign('order_id')
                ->references('id')->on('orders')
                ->onUpdate('no action')->onDelete('no action');

            $table->foreign('product_id')
                    ->references('id')->on('products')
                    ->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
