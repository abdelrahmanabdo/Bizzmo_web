<?php

//require __DIR__.'\bootstrap\autoload.php';
$app = require_once __DIR__.'\bootstrap\app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use App\Helpers\SapConnection;
use App\Company;

$sap_connection = SapConnection::getConnection();
$func = $sap_connection->getFunction('ZCREATE_VENDOR');
$result = $func->invoke([			
	'BUKRS_001' => '1100',
	'KTOKK_002' => 'Z001',
	'ANRED_003' => 'COMPANY',
	'NAME1_004' => $this->company->companyname,
	'SORTL_005' => 'BIZ' . $this->company->id,
	'STRAS_006' => $this->company->address,
	'PFACH_007' => '111',
	'ORT01_008' => 'Riyadh',
	'PSTLZ_009' => '62',
	'ORT02_010' => 'Dist',
	'LAND1_011' => 'SA',
	'REGIO_012' => '01',
	'SPRAS_013' => 'EN',
	'AKONT_014' => '208000',
	'ZUAWA_015' => '001',
	'FDGRV_016' => '1001',
	'ZTERM_017' => '0001',
	'REPRF_018' => 'X'
]);
$this->company->sapvendornumber = $result['LIFNR'];
$this->company->save();
var_dump($result);
die;