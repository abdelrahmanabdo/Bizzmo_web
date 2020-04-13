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

// $arrcompanies = Auth::user()->companies->pluck('id');
if (isset($purchaseorder)) {
	$buyerCompany = $purchaseorder->company;
} else {
	$buyerCompany = Auth::user()->getBuyerCompany();
}
$companies = Company::with('vendors')->where('id', $buyerCompany->id)->where('companytype_id', '!=', 2)->where('active', 1)->get();
$vendors = Vendor::where('companytype_id', 2)->where('active', 1)->get();
?>

<!-- Button trigger modal -->
<!-- <button type="button" class="lnk-button" data-toggle="modal" data-target="#manageSuppliersModal">
  Manage Suppliers
</button> -->

<!-- Modal -->
<div class="modal fade" id="manageSuppliersModal" role="dialog" aria-labelledby="manageSuppliersModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="manageSuppliersModalLabel" style="display: inline-block;">Manage Suppliers for <strong>{{ $buyerCompany->companyname }}</strong></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('purchaseorders.mysuppliers', 
          [
            'buyer_company' => $buyerCompany,
            'companies' => $companies,
            'arrvendors' => $vendors->pluck('companyname', 'id')
          ])
      </div>
    </div>
  </div>
</div>