<?php

namespace App\Helpers;

use SAPNWRFC\Connection as connection;

class SapConnection 
{      
    /**
     * Create a new sap connection instance.
     *
     * @return connection
     */
    public static function getConnection()
    {
        return new connection(self::setConfig());
    }

    private static function setConfig() 
    {
        return [
            'ashost' => env('SAP_HOST'),
            'sysnr'  => env('SAP_SYSNO'),
            'client' => env('SAP_CLIENT'),
            'user' => env('SAP_USER', 'null'),
            'passwd' => env('SAP_PASSWORD', 'null'),
            'trace'  => connection::TRACE_LEVEL_OFF
        ];
    }
}