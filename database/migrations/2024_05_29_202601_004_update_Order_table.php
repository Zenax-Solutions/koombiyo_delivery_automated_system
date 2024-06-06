<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Order', function (Blueprint $table) {
            $table
                ->bigInteger('district_id')
                ->unsigned()
                ->after('delivery_address');
            $table
                ->bigInteger('city_id')
                ->unsigned()
                ->after('district_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Order', function (Blueprint $table) {
            $table->dropColumn('district_id');
            $table->dropColumn('city_id');
            $table->dropForeign('order_city_id_foreign');
            $table->dropForeign('order_district_id_foreign');
        });
    }
};
