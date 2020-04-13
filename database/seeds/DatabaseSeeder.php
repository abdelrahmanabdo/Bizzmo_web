<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersInitTableSeeder::class);
		$this->call(RolesTableSeederInit::class);
		$this->call(PermissionRoleTableSeederInit::class);        
		$this->call(RoleUserTableSeederInit::class);		
        $this->call(AttachmenttypesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(ModulesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(CreditrequesttypesTableSeeder::class);
		$this->call(CreditstatusesTableSeeder::class);
        $this->call(SecuritytypesTableSeeder::class);
        $this->call(StatusesTableSeeder::class);
        $this->call(TimeslotsTableSeeder::class);        
        $this->call(MargindeposittypesTableSeeder::class);		
		$this->call(CompanytypesTableSeeder::class);
        $this->call(RangesTableSeeder::class);		
        $this->call(IncotermsTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
		$this->call(UnitsTableSeeder::class);
		$this->call(MaterialgroupsTableSeeder::class);        
        $this->call(IncomestatementitemsTableSeeder::class);
		$this->call(BalancesheetitemsTableSeeder::class);
        $this->call(PaymenttermsTableSeeder::class);
		$this->call(IndustriesTableSeeder::class);
        $this->call(BrandsTableSeeder::class);
        $this->call(TenorsTableSeeder::class);
		$this->call(SettingsTableSeeder::class);
		$this->call(AddRightSignatureToken::class);
		$this->call(ScoreFactorsTableSeeder::class);
		$this->call(CompanyRelationTypesTableSeeder::class);
		$this->call(ScoresTableSeeder::class);
		$this->call(BuyertypesTableSeeder::class);
		$this->call(SuppliertypesTableSeeder::class);
		$this->call(DeliverytypesTableSeeder::class);
		$this->call(FreightexpensesTableSeeder::class);
		$this->call(LarametricsNotificationsTableSeeder::class);
		$this->call(PhoneInitTableSeeder::class);
		
		$this->call(UsersTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CompanyownersTableSeeder::class);
        $this->call(CompanydirectorsTableSeeder::class);
        $this->call(CompanytopproductsTableSeeder::class);
        $this->call(CompanytopcustomersTableSeeder::class);
        $this->call(CompanyVendorTableSeeder::class);
        $this->call(AttachmentsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(RoleUserTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);                
        $this->call(CompanyPaymenttermTableSeeder::class);
        $this->call(ShippingaddressesTableSeeder::class);        
        $this->call(PhoneTableSeeder::class);
        
        $this->call(CompanyBrandTableSeeder::class);
        $this->call(CompanyIndustryTableSeeder::class);
        $this->call(CompanybeneficialsTableSeeder::class);
        $this->call(CompanytopsuppliersTableSeeder::class);        
        $this->call(IncomestatementsTableSeeder::class);
        $this->call(BalancesheetsTableSeeder::class);
        $this->call(CompanyDeliveryTableSeeder::class);        
        $this->call(PortCodesTableSeeder::class);
        $this->call(ForwarderservicesTableSeeder::class);
    }
}
