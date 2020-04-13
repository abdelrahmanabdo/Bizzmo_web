@section('styles')
<style>
  .lnk-button {
    background: none;
    border: none;
    color: black;
    text-decoration: underline;
  }
</style>
@stop
<?php
use App\Company;
use App\Vendor;

if (isset($quotation)) {
	$company = $quotation->company;
} else {
	$company = Auth::user()->getSupplierCompany();
}
$companies = Company::with('vendors')->where('id', $company->id)->where('companytype_id', '!=', 1)->where('active', 1)->get();
$favourites = Vendor::where('companytype_id', 2)->where('active', 1)->get();
?>

<!-- Button trigger modal -->
<!-- <button type="button" class="lnk-button" data-toggle="modal" data-target="#manageBuyersModal">
  Manage Suppliers
</button> -->

<!-- Modal -->
<div class="modal fade" id="manageBuyersModal" role="dialog" aria-labelledby="manageBuyersModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageBuyersModalLabel" style="display: inline-block;">Manage Suppliers for <strong>{{ $company->companyname }}</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('quotations.mybuyers', 
          [
            'company' => $company,
            'companies' => $companies
          ])
      </div>
    </div>
  </div>
</div>