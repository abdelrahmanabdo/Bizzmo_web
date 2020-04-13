<?php
	if (Gate::any(['pt_as', 'po_rc'])) {
		$outstandingURL =  "/data-reporting/outstanding/$company->id";
		$creditCheckURL = "/credit/company/$company->id";
	} else {
		$outstandingURL = "/data-reporting/outstanding";
		$creditCheckURL = "/credit/check";
	}
?>

<div class="row">
	<div class="col-md-6">
		<div class="row" style="margin-bottom: 25px">
			<div class="col-md-6">
				<h4>Company Info:</h4>
			</div>
			<div class="col-md-6" style="padding: 10 0px">
				<p>{{ $company->companyname }}</p>
				<p>{{ $company->email }}</p>
			</div>
		</div>

		@if(isset($customerReport))
		<?php
			$creditLimit = $company->creditlimit;
			$balance = $customerReport->getBalance();
			$deltaLimit = $creditLimit - $balance;
			$openPOsValue = $company->creditpos->sum('grand_total');
			$availableCredit = $deltaLimit - $openPOsValue;
			$isOverDueItems = $customerReport->isOverDueItems();
			$oldestOpenItem = date("d/m/Y", strtotime($customerReport->getOldestOpenItem()));
			?>
		<div class="row" style="margin-bottom: 25px">
			@if(isset($vendorReport))
			<h4 class="bm-pg-title">Customer</h4>
			@endif
			<div class="col-md-6">
				<h4>Balance:</h4>
				<h4>Available credit limit:</h4>
			</div>
			<div class="col-md-3" style="text-align: right;">
				<h4>
					<strong>{{ number_format($balance, 2, '.', ',') }}</strong>
				</h4>
				<h4>
					<strong>
						<p class="<?= $availableCredit < 0 ? 'red' : 'text-success' ?>">
							{{ number_format($availableCredit, 2, '.', ',') }}
						</p>
					</strong>
				</h4>
			</div>
			<div class="col-md-2">
				<a href="<?= $outstandingURL ?>"><p style="text-decoration: underline; padding-top: 10px"><span class="view-icon" title="Statement of Outstanding"></span></p></a>
				<a href="<?= $creditCheckURL ?>"><p style="text-decoration: underline; padding-top: 5px"><span class="view-icon" title="Credit check"></span></p></a>
			</div>
		</div>
		@endif

		@if(isset($vendorReport))
		<div class="row" style="margin-bottom: 25px">
			@if(isset($customerReport))
			<h4 class="bm-pg-title">Vendor</h4>
			@endif
			<div class="col-md-6">
				<h4>Balance:</h4>
			</div>
			<div class="col-md-3" style="text-align: right;">
				<h4>
					<strong>{{ number_format($vendorReport->getBalance(), 2, '.', ',') }}</strong>
				</h4>
			</div>
			<div class="col-md-2">
				<a href="<?= $outstandingURL ?>"><p style="text-decoration: underline; padding-top: 10px"><span class="view-icon" title="Statement of Outstanding"></span></p></a>
			</div>
		</div>
		@endif
	</div>

	<div class="col-md-6">
		@if(isset($customerReport))
		<div style="max-width: 500px">	
			<canvas id="myChart"></canvas>
		</div>
		@endif
	</div>
</div>


<div class="row">
	<h3 class="bm-pg-title">Latest transactions</h3>
	@include('purchaseorders.table', ['purchaseorders' => $latestTransactions, 'title' => null])
</div>

@if(isset($customerReport))
<script type="text/javascript">
var creditLimit = <?= $company->creditlimit ?>;
var availableCredit = <?= $availableCredit ?>;
var exceedsLimit = availableCredit < 0;

if(!exceedsLimit) {
	creditLimit = creditLimit - availableCredit
}

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
	type: exceedsLimit ? "horizontalBar" : "doughnut",
	data: {
		labels: [
			exceedsLimit ? "Credit limit" : "Used credit limit",
			"Available credit limit"],
		datasets: [{
			data: [
				creditLimit,
				availableCredit
			],
			backgroundColor: [
				'#3f64a1',
				exceedsLimit ? "#f0433e" : "#7ccee1"
			],
			borderWidth: 1
		}]
	},
	options: {
		responsive: true,
		legend: {
			display: exceedsLimit ? false : true,
			position: 'bottom',
			onClick: (e) => e.stopPropagation()
		},
		animation: {
			animateScale: true,
			animateRotate: true
		}
	}
});
</script>
@endif
