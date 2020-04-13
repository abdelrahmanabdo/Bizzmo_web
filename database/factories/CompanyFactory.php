<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(\App\Company::class, function (Faker $faker) {
    return [
        'companyname' => $faker->company,
        'address' => $faker->streetAddress,
        'district' => $faker->city,
        'city_id' => '3796',
        'country_id' => '229',
        'companytype_id' => '2',
        'pobox' => '123456',
        'email' => $faker->companyEmail,
        'phone' => $faker->phoneNumber,
        'fax' => $faker->phoneNumber,
        'license' => '12',
        'tax' => 'lorem-123',
        'vatno' => NULL,
        'vat' => '5',
        'incorporated' => '2010-06-30',
        'employees' => '11',
        'creditlimit' => '1000',
        'margin' => '0',
        'payment' => '0',
        'website' => $faker->domainName,
        'operating' => NULL,
        'accountname' => $faker->name,
        'bankname' => $faker->company,
        'accountnumber' => $faker->bankAccountNumber,
        'iban' => 'lorem',
        'routingcode' => 'lorem',
        'swift' => 'lorem',
        'otp' => NULL,
        'sapnumber' => '0100004989',
        'sapvendornumber' => '0001000371',
        'active' => '1',
        'confirmed' => '1',
        'sameowner' => '1',
        'basicinfo' => '1',
        'shareholders' => '1',
        'beneficialowners' => '1',
        'directors' => '1',
        'business' => '1',
        'banks' => '1',
        'tenant_id' => '7',
        'vendor_signed' => '1',
        'customer_signed' => '1',
        'created_by' => '7',
        'updated_by' => '7'
    ];
});
