<?php

use Illuminate\Database\Seeder;

class IndustriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('industries')->delete();
        
        \DB::table('industries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Architecture & Interior Design',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Automotive',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Beauty & Health',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Books',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Cleaning Services',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Clothing & Fashion',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Construction Equipment & Materials',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Consulting',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Courses and Training',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Crafts, Collectibles & Art',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Electrical & Lighting',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Electronics, Mobile & Computers',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'Financial Services',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'Food & Beverage',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'Furniture',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'Gaming',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'Gift Cards, Tickets, and Vouchers',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'Graphics & Design',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'Home & Garden',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'Human Resources',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'Insurance',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'Internet and Telecom',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'Jewerly & watches',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'Legal',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'Machinery, Equipment, and Industrial Parts',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'Marketing',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'Medical & Healthcare',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'Movies & music',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'Office Stationary & Supplies',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'Packaging',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'Pharmaceuticals & Medince',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'Property & Realestate',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'Recruitment',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'Restoration & Repairs',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'Security Services',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'Shipping and Logistics Services',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'Sporting Goods & Outdoor',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'Technology Services',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'Toys, Kids and Baby',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'Transportation',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'Travel',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'Writing & Translation',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'Others',
                'active' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}