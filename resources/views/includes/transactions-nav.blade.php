<div class="page-header-container">
    <div class="item"><a href="{{url('/transactions')}}" @if(\Request::is('transactions')) class="active" @endif>Pending Transactions</a></div>
    <div class="item"><a href="{{url('/purchaseorders')}}" @if(\Request::is('purchaseorders')) class="active" @endif>Purchase Orders</a></div>
    <div class="item"><a href="{{url('/quotations/create')}}" @if(\Request::is('quotations/create')) class="active" @endif>Request To Sell</a></div>
    <div class="item"><a href="{{url('/quotations')}}" @if(\Request::is('quotations')) class="active" @endif>Quotations</a></div>
</div>