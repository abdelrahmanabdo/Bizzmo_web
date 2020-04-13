<?php

use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permission_role')->delete();
        
        \DB::table('permission_role')->insert(array (
            0 => 
            array (
                'id' => 1,
                'permission_id' => 8,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            1 => 
            array (
                'id' => 2,
                'permission_id' => 20,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            2 => 
            array (
                'id' => 3,
                'permission_id' => 49,
                'role_id' => 1,
                'created_at' => '2018-09-26 14:21:00',
                'updated_at' => '2018-09-26 14:21:00',
            ),
            3 => 
            array (
                'id' => 4,
                'permission_id' => 8,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            4 => 
            array (
                'id' => 5,
                'permission_id' => 20,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            5 => 
            array (
                'id' => 6,
                'permission_id' => 50,
                'role_id' => 2,
                'created_at' => '2018-09-26 14:21:20',
                'updated_at' => '2018-09-26 14:21:20',
            ),
            6 => 
            array (
                'id' => 7,
                'permission_id' => 7,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            7 => 
            array (
                'id' => 8,
                'permission_id' => 8,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            8 => 
            array (
                'id' => 9,
                'permission_id' => 20,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            9 => 
            array (
                'id' => 10,
                'permission_id' => 21,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            10 => 
            array (
                'id' => 11,
                'permission_id' => 35,
                'role_id' => 3,
                'created_at' => '2018-09-26 14:21:43',
                'updated_at' => '2018-09-26 14:21:43',
            ),
            11 => 
            array (
                'id' => 12,
                'permission_id' => 8,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            12 => 
            array (
                'id' => 13,
                'permission_id' => 20,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            13 => 
            array (
                'id' => 14,
                'permission_id' => 32,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            14 => 
            array (
                'id' => 15,
                'permission_id' => 35,
                'role_id' => 4,
                'created_at' => '2018-09-26 14:22:11',
                'updated_at' => '2018-09-26 14:22:11',
            ),
            15 => 
            array (
                'id' => 16,
                'permission_id' => 40,
                'role_id' => 5,
                'created_at' => '2018-09-26 14:22:26',
                'updated_at' => '2018-09-26 14:22:26',
            ),
            16 => 
            array (
                'id' => 17,
                'permission_id' => 41,
                'role_id' => 5,
                'created_at' => '2018-09-26 14:22:26',
                'updated_at' => '2018-09-26 14:22:26',
            ),
            17 => 
            array (
                'id' => 18,
                'permission_id' => 1,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            18 => 
            array (
                'id' => 19,
                'permission_id' => 2,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            19 => 
            array (
                'id' => 20,
                'permission_id' => 3,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            20 => 
            array (
                'id' => 21,
                'permission_id' => 4,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            21 => 
            array (
                'id' => 22,
                'permission_id' => 5,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            22 => 
            array (
                'id' => 23,
                'permission_id' => 6,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            23 => 
            array (
                'id' => 24,
                'permission_id' => 9,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            24 => 
            array (
                'id' => 25,
                'permission_id' => 10,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            25 => 
            array (
                'id' => 26,
                'permission_id' => 11,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            26 => 
            array (
                'id' => 27,
                'permission_id' => 17,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            27 => 
            array (
                'id' => 28,
                'permission_id' => 22,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            28 => 
            array (
                'id' => 29,
                'permission_id' => 25,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            29 => 
            array (
                'id' => 30,
                'permission_id' => 26,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            30 => 
            array (
                'id' => 31,
                'permission_id' => 27,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            31 => 
            array (
                'id' => 35,
                'permission_id' => 31,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            32 => 
            array (
                'id' => 37,
                'permission_id' => 42,
                'role_id' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            33 => 
            array (
                'id' => 38,
                'permission_id' => 9,
                'role_id' => 8,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            34 => 
            array (
                'id' => 39,
                'permission_id' => 10,
                'role_id' => 8,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            35 => 
            array (
                'id' => 40,
                'permission_id' => 11,
                'role_id' => 8,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            36 => 
            array (
                'id' => 41,
                'permission_id' => 22,
                'role_id' => 9,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            37 => 
            array (
                'id' => 42,
                'permission_id' => 12,
                'role_id' => 10,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            38 => 
            array (
                'id' => 43,
                'permission_id' => 23,
                'role_id' => 11,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            39 => 
            array (
                'id' => 44,
                'permission_id' => 1,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            40 => 
            array (
                'id' => 46,
                'permission_id' => 3,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            41 => 
            array (
                'id' => 47,
                'permission_id' => 4,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            42 => 
            array (
                'id' => 48,
                'permission_id' => 5,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            43 => 
            array (
                'id' => 49,
                'permission_id' => 6,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            44 => 
            array (
                'id' => 50,
                'permission_id' => 9,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            45 => 
            array (
                'id' => 51,
                'permission_id' => 10,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            46 => 
            array (
                'id' => 52,
                'permission_id' => 11,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            47 => 
            array (
                'id' => 53,
                'permission_id' => 17,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            48 => 
            array (
                'id' => 54,
                'permission_id' => 22,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            49 => 
            array (
                'id' => 55,
                'permission_id' => 25,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            50 => 
            array (
                'id' => 56,
                'permission_id' => 26,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            51 => 
            array (
                'id' => 57,
                'permission_id' => 27,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            52 => 
            array (
                'id' => 61,
                'permission_id' => 31,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            53 => 
            array (
                'id' => 63,
                'permission_id' => 42,
                'role_id' => 13,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            54 => 
            array (
                'id' => 64,
                'permission_id' => 9,
                'role_id' => 14,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            55 => 
            array (
                'id' => 65,
                'permission_id' => 10,
                'role_id' => 14,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            56 => 
            array (
                'id' => 66,
                'permission_id' => 11,
                'role_id' => 14,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            57 => 
            array (
                'id' => 67,
                'permission_id' => 22,
                'role_id' => 15,
                'created_at' => '2018-09-26 14:49:25',
                'updated_at' => '2018-09-26 14:49:25',
            ),
            58 => 
            array (
                'id' => 68,
                'permission_id' => 12,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            59 => 
            array (
                'id' => 69,
                'permission_id' => 13,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            60 => 
            array (
                'id' => 70,
                'permission_id' => 14,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            61 => 
            array (
                'id' => 71,
                'permission_id' => 15,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            62 => 
            array (
                'id' => 72,
                'permission_id' => 16,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            63 => 
            array (
                'id' => 73,
                'permission_id' => 18,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            64 => 
            array (
                'id' => 75,
                'permission_id' => 24,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            65 => 
            array (
                'id' => 77,
                'permission_id' => 37,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            66 => 
            array (
                'id' => 78,
                'permission_id' => 38,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            67 => 
            array (
                'id' => 79,
                'permission_id' => 39,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            68 => 
            array (
                'id' => 87,
                'permission_id' => 43,
                'role_id' => 17,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            69 => 
            array (
                'id' => 88,
                'permission_id' => 12,
                'role_id' => 18,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            70 => 
            array (
                'id' => 89,
                'permission_id' => 23,
                'role_id' => 19,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            71 => 
            array (
                'id' => 90,
                'permission_id' => 12,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            72 => 
            array (
                'id' => 91,
                'permission_id' => 13,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            73 => 
            array (
                'id' => 92,
                'permission_id' => 14,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            74 => 
            array (
                'id' => 93,
                'permission_id' => 15,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            75 => 
            array (
                'id' => 94,
                'permission_id' => 16,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            76 => 
            array (
                'id' => 95,
                'permission_id' => 18,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            77 => 
            array (
                'id' => 96,
                'permission_id' => 23,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            78 => 
            array (
                'id' => 97,
                'permission_id' => 24,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            79 => 
            array (
                'id' => 102,
                'permission_id' => 45,
                'role_id' => 16,
                'created_at' => '2018-09-26 14:53:57',
                'updated_at' => '2018-09-26 14:53:57',
            ),
            80 => 
            array (
                'id' => 107,
                'permission_id' => 37,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            81 => 
            array (
                'id' => 108,
                'permission_id' => 38,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            82 => 
            array (
                'id' => 109,
                'permission_id' => 39,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            83 => 
            array (
                'id' => 110,
                'permission_id' => 45,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            84 => 
            array (
                'id' => 111,
                'permission_id' => 46,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            85 => 
            array (
                'id' => 112,
                'permission_id' => 47,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            86 => 
            array (
                'id' => 113,
                'permission_id' => 48,
                'role_id' => 6,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            87 => 
            array (
                'id' => 115,
                'permission_id' => 46,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            88 => 
            array (
                'id' => 116,
                'permission_id' => 47,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            89 => 
            array (
                'id' => 117,
                'permission_id' => 48,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            90 => 
            array (
                'id' => 118,
                'permission_id' => 2,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            91 => 
            array (
                'id' => 119,
                'permission_id' => 23,
                'role_id' => 12,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            92 => 
            array (
                'id' => 120,
                'permission_id' => 11,
                'role_id' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            93 => 
            array (
                'id' => 121,
                'permission_id' => 11,
                'role_id' => 13,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            94 => 
            array (
                'id' => 122,
                'permission_id' => 12,
                'role_id' => 17,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            95 => 
            array (
                'id' => 123,
                'permission_id' => 43,
                'role_id' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
            96 => 
            array (
                'id' => 124,
                'permission_id' => 12,
                'role_id' => 7,
                'created_at' => '2018-09-26 14:39:50',
                'updated_at' => '2018-09-26 14:39:50',
            ),
        ));
        
        
    }
}