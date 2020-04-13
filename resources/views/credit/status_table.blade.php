<?php
	// Calculate delta limit
	$creditLimit = $company->creditlimit;
	$balance = $report->getBalance();
	$deltaLimit = $creditLimit - $balance;
	$openPOsValue = $company->creditpos->sum('grand_total');
	$diff = $deltaLimit - $openPOsValue;
	$isOverDueItems = $report->isOverDueItems();
	$oldestOpenItem = date("d/m/Y", strtotime($report->getOldestOpenItem()));
?>

<table id="mytable" class="table table-striped table-bordered table-hover" style="border-top: 1px solid #d4d4d4;">				
  <tbody>	
    <tr>
      <td>Credit limit</td>
      <td align="right"> {{ number_format($creditLimit, 2, '.', ',') }} </td>
    </tr>
    <tr>
      <td>Balance</td>
      <td align="right"> {{ number_format($balance, 2, '.', ',') }} </td>
    </tr>
    <tr>
      <td>Open POs value</td>
      <td align="right"> {{ number_format($openPOsValue, 2, '.', ',') }} </td>
    </tr>
    <tr>
      <td>Available credit limit</td>
      @if($diff < 0)
        <td align="right"> <p class="red"><b>{{ number_format($diff, 2, '.', ',') }}</b></p> </td>
      @else
        <td align="right"> <p class="text-success"><b>{{ number_format($diff, 2, '.', ',') }}</b></p> </td>
      @endif	
    </tr>
    <tr>
      <td>Overdue items</td>
      @if($isOverDueItems)
        <td align="right"> <p class="red"><b>since {{ $oldestOpenItem }}</b></p> </td>
      @else
        <td align="right"> <p class="text-success"><b>No overdue items</b></p> </td>
      @endif
    </tr>
  </tbody>
</table>