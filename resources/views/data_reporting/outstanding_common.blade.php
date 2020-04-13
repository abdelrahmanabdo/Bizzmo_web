@if (!isset($customerItems) && !isset($vendorItems))
	<p>You don't have items yet</p>
@else
	@if (isset($customerItems))
		@if (isset($vendorItems))
		<h4>Customer</h4><br/>
		@endif
		@include('data_reporting.items_table', ['key' => 'customer', 'items' => $customerItems])
		<hr/>
	@endif
	@if (isset($vendorItems))
		@if (isset($customerItems))
		<h4>Vendor</h4><br/>
		@endif
		@include('data_reporting.items_table', ['key' => 'vendor', 'items' => $vendorItems])			
	@endif
@endif
