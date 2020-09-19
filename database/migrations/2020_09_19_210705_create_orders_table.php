<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number')->index();
            $table->unsignedsmallInteger('status')->index()->default(0);
            $table->unsignedBigInteger('supplier_id')->index();
            $table->timestamps();

            // Foreign key(s)
            $table->foreign('supplier_id')
                ->references('id')->on('suppliers')
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
        Schema::dropIfExists('orders');
    }
}
