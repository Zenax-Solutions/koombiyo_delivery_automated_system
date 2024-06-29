<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Order', function (Blueprint $table) {
            $table->bigInteger('branch_id')->unsigned();
            $table->id();
            $table->string('waybill_id')->nullable();
            $table->string('order_number')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('delivery_address')->nullable();
            $table->bigInteger('district_id')->unsigned();
            $table->bigInteger('city_id')->unsigned();
            $table->string('receiver_phone')->nullable();
            $table
                ->decimal('cod')
                ->default(0)
                ->nullable();
            $table->json('description')->nullable();
            $table
                ->decimal('actual_value')
                ->default(0)
                ->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('branch_id')
                ->references('id')
                ->on('Branch')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Order');
    }
};
