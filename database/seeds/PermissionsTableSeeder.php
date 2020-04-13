<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'co_cr',
                'display_name' => 'Create buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'co_ch',
                'display_name' => 'Change buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'co_vw',
                'display_name' => 'View buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'cr_cr',
                'display_name' => 'Create credit request',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'cr_ch',
                'display_name' => 'Change credit request',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'cr_vw',
                'display_name' => 'View credit request',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'cr_ap',
                'display_name' => 'Approve credit request',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'co_vw',
                'display_name' => 'View buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'po_cr',
                'display_name' => 'Create purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'po_ch',
                'display_name' => 'Change purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'po_vw',
                'display_name' => 'View purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'vp_vw',
                'display_name' => 'View purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'co_cr',
                'display_name' => 'Create supplier',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'co_ch',
                'display_name' => 'Change supplier',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'co_vw',
                'display_name' => 'View supplier',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'vp_ch',
                'display_name' => 'Change purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'co_co',
                'display_name' => 'Confirm buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'co_co',
                'display_name' => 'Confirm supplier',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'mg_mg',
                'display_name' => 'Material group manager',
                'description' => NULL,
                'active' => 0,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'po_vm',
                'display_name' => 'View purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            20 => 
            array (
                'id' => 21,
                'name' => 'po_rc',
                'display_name' => 'Credit release purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            21 => 
            array (
                'id' => 22,
                'name' => 'po_rl',
                'display_name' => 'Release purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            22 => 
            array (
                'id' => 23,
                'name' => 'po_rl',
                'display_name' => 'Release purchase order',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            23 => 
            array (
                'id' => 24,
                'name' => 'vp_ap',
                'display_name' => 'Approve or reject purchase order',
                'description' => 'Vendor approve and reject PO',
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            24 => 
            array (
                'id' => 25,
                'name' => 'us_cr',
                'display_name' => 'Create user',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            25 => 
            array (
                'id' => 26,
                'name' => 'us_ch',
                'display_name' => 'Change user',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            26 => 
            array (
                'id' => 27,
                'name' => 'us_vw',
                'display_name' => 'View user',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            27 => 
            array (
                'id' => 28,
                'name' => 'ro_cr',
                'display_name' => 'Create role',
                'description' => NULL,
                'active' => 0,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            28 => 
            array (
                'id' => 29,
                'name' => 'ro_ch',
                'display_name' => 'Change role',
                'description' => NULL,
                'active' => 0,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            29 => 
            array (
                'id' => 30,
                'name' => 'ro_vw',
                'display_name' => 'View role',
                'description' => NULL,
                'active' => 0,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            30 => 
            array (
                'id' => 31,
                'name' => 'us_as',
                'display_name' => 'Assign role to user',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            31 => 
            array (
                'id' => 32,
                'name' => 'pt_as',
                'display_name' => 'Assign payment term to buyer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            32 => 
            array (
                'id' => 33,
                'name' => 'pt_mg',
                'display_name' => 'Manage payment terms',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            33 => 
            array (
                'id' => 34,
                'name' => 'pt_ch',
                'display_name' => 'Change payment term',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            34 => 
            array (
                'id' => 35,
                'name' => 'pt_vw',
                'display_name' => 'View payment terms',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            35 => 
            array (
                'id' => 36,
                'name' => 'lg_vw',
                'display_name' => 'View system log',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            36 => 
            array (
                'id' => 37,
                'name' => 'qu_cr',
                'display_name' => 'Create quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            37 => 
            array (
                'id' => 38,
                'name' => 'qu_ch',
                'display_name' => 'Change quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            38 => 
            array (
                'id' => 39,
                'name' => 'qu_vw',
                'display_name' => 'View quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            39 => 
            array (
                'id' => 40,
                'name' => 'su_vw',
                'display_name' => 'View support',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            40 => 
            array (
                'id' => 41,
                'name' => 'su_ch',
                'display_name' => 'Change support',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            41 => 
            array (
                'id' => 42,
                'name' => 'fi_cl',
                'display_name' => 'Finance clerk',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            42 => 
            array (
                'id' => 43,
                'name' => 'fi_cl',
                'display_name' => 'Finance clerk',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            43 => 
            array (
                'id' => 44,
                'name' => 'fi_vw',
                'display_name' => 'Finance view',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            44 => 
            array (
                'id' => 45,
                'name' => 'qu_rl',
                'display_name' => 'Release quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 2,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            45 => 
            array (
                'id' => 46,
                'name' => 'cq_vw',
                'display_name' => 'View quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            46 => 
            array (
                'id' => 47,
                'name' => 'cq_ch',
                'display_name' => 'Change quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            47 => 
            array (
                'id' => 48,
                'name' => 'cq_ap',
                'display_name' => 'Approve or reject quotation',
                'description' => NULL,
                'active' => 1,
                'module_id' => 1,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            48 => 
            array (
                'id' => 49,
                'name' => 'fi_ap',
                'display_name' => 'Accounts Payable',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            49 => 
            array (
                'id' => 50,
                'name' => 'fi_ar',
                'display_name' => 'Accounts Receivable',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            50 => 
            array (
                'id' => 51,
                'name' => 'co_cr',
                'display_name' => 'Create forwarder',
                'description' => NULL,
                'active' => 1,
                'module_id' => 6,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            51 => 
            array (
                'id' => 52,
                'name' => 'co_ch',
                'display_name' => 'Change forwarder',
                'description' => NULL,
                'active' => 1,
                'module_id' => 6,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            52 => 
            array (
                'id' => 53,
                'name' => 'co_vw',
                'display_name' => 'View forwarder',
                'description' => NULL,
                'active' => 1,
                'module_id' => 6,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
            53 => 
            array (
                'id' => 54,
                'name' => 'cr_of',
                'display_name' => 'Credit Officer',
                'description' => NULL,
                'active' => 1,
                'module_id' => 3,
                'created_at' => '2017-10-01 00:00:00',
                'updated_at' => '2017-10-01 00:00:00',
            ),
        ));
        
        
    }
}