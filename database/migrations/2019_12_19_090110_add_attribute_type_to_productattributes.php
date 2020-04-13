<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttributeTypeToProductattributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productattributes', function (Blueprint $table) {
            $table->string('attribute_type');
        });

        //Insert generic system attributes in productattributes table
        DB::table('productattributes')->insert(
            [
              [
                'attribute' => 'product_line',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'brand',
                'attribute_type'=> 'select',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'material',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'model',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'weight',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'warranty',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'UOM',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'manufacturer',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'country_of_region',
                'attribute_type'=> 'select',
                'system' => 1,
                'active' => 1
              ],
              [
                'attribute' => 'HS_code',
                'attribute_type'=> 'text',
                'system' => 1,
                'active' => 1
              ]
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productattributes', function (Blueprint $table) {
            $table->dropColumn('attribute_type');
        });
    }
}
