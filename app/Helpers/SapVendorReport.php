<?php

namespace App\Helpers;

class SapVendorReport
{
    public $report = null;

    public function __construct($args)
    {
        $connection = SapConnection::getConnection();
        $func = $connection->getFunction('ZVENDOR_REPORT');
        $this->report = $func->invoke($args);

        return $this->report;
    }

    public function getBalance()
    {
        return $this->report["BALANCE"];
    }

    public function getCurrency()
    {
        return $this->report["CURRENCY"];
    }

    public function getOldestOpenItem()
    {
        return $this->report["OLDESTOPENITEM"];
    }

    public function getItems() {
        return $this->report["VEND_LIST"];
    }

    public function isOverDueItems()
    {
        if($this->report["OLDESTOPENITEM"] == 0)
            return false;
        return date("Ymd") > date("Ymd", strtotime($this->report["OLDESTOPENITEM"])) ? true : false;
    }
}