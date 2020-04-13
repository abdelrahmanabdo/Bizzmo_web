<html>
<head>
	<title>{{ config('app.companyname') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="To the new era of business growth" />
	<meta name="twitter:card" value="summary">
	<meta property="og:title" content="Bizzmo | Extend your business"/>
	<meta property="og:site_name" content="Bizzmo"/>
	<meta property="og:image" content="{{ asset('images/bizzmo-logo.png') }}"/>
	<meta property="og:url" content="{{ URL::to('/') }}" />
	<meta property="og:description" content="Bizzmo, to the new era of business growth" />
	<link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
	<link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon">
	<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}" />
	<link rel="stylesheet" href="{{asset('css/owl.carousel.css')}}" />
	{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}

	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Open+Sans:400,600" rel="stylesheet">
	
	<style>
	body {
		padding-top: 70px;
	}
	</style>
	@yield('styles')
</head>
<body>
	@if (\Request::is(['chat', 'chat/*']))
	@else
		<div></div>
	@endif
	<div id="app">
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				@if (!Auth::guest())
				<button type="button" class="navbar-toggle collapsed hidden-lg" data-toggle="collapse" data-target="#menu-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				@endif
				<div class="flex-container">
					<a href="/" style="margin: auto 0"> 
						<img src="{{ asset('images/bizzmo-logo.png') }}" class="pull-left logo">
					</a>
					<a class="navbar-brand visible-lg" href="/">Bizzmo</a>
				</div>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			@auth
				@php
					$isSupport = \Request::is(['supports', 'supports/*']);
					$isChat = \Request::is(['chat', 'chat/*']);
					$isCustomerSupport = \Request::is(['support/*']);
					$isUser = \Request::is(['users', 'users/*', 'roles','roles/*']);
					$isTransaction = \Request::is(['transactions/*','transactions', 'purchaseorders/*', 'purchaseorders', 'quotations/*', 'quotations']) && !\Request::is('purchaseorders/pending*');
					// $isTransaction = \Request::is(['transactions/*','transactions', 'quotations/*', 'quotations', 'purchaseorders/*', 'purchaseorders']) && !\Request::is('purchaseorders/pending*');
					$isCreditRequest = \Request::is(['creditrequests/*', 'creditrequests']);
					$isVendor = \Request::is(['vendors/*', 'vendors']);
					$isCompany = \Request::is(['companies/*', 'companies', 'company/*/*', 'company/*']);
					$isPendingRequests = \Request::is('pending-requests');
					$isSiteVisit = \Request::is(['calendar', 'calendar/*', 'calendar/block-appointments', 'calendar/block-appointments/*']);
					$isHome = \Request::is(['', '/', 'home']);
					$isShippingAddress = \Request::is(['shippingaddresses', 'shippingaddresses/*']);
					$isPickupAddress = \Request::is(['pickupaddresses', 'pickupaddresses/*']);
					$isVatExempt = \Request::is(['vatexempt', 'vatexempt/*']);
					$isMySuppliers = \Request::is('companies/mysuppliers/*');
					$isMyBuyers = \Request::is('companies/mybuyers/*');
					$isDataReporting = \Request::is('data-reporting') || \Request::is('data-reporting/*') || \Request::is('purchaseorders/pending*');
					$isCreditCheck = \Request::is('credit/*');
					$isProfile = \Request::is('profile') || \Request::is('profile/*');
					$isLogs = \Request::is('logs') || \Request::is('logs/*') || \Request::is('log-viewer') || \Request::is('log-viewer/*')
					 || \Request::is('phpinfo') || \Request::is('phpinfo/*') || \Request::is('admin/metrics') || \Request::is('admin/metrics/*');

					$userIsAR = Gate::allows('fi_ar'); 
					// User Company Vars
					$hasCompany = Auth::user()->companies->count();
					$userCompany = $hasCompany ? Auth::user()->companies->first() : null;
					$hasReadyBuyerCompany = Auth::user()->hasReadyBuyerCompany();
					$buyerCompany =  Auth::user()->getBuyerCompany();
					$supplierCompany =  Auth::user()->getSupplierCompany();
					$buyerCompanyId = $hasReadyBuyerCompany ? $buyerCompany->id : null;
				@endphp
			@endauth
			@if (!Auth::guest())
			<div class="collapse navbar-collapse" id="menu-collapse">
				<ul class="nav navbar-nav navbar-right">
					<li class="navbar-item bm-nav-item visible-xs <?= ($isProfile) ? 'bm-active': '';?>">
						<a class="bm-hr-nav-link" href="/profile">Profile</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.profile', ['isProfile' => $isProfile])
						</ul>
					</li>
					@if (Gate::any(['su_ch', 'su_vw']))
					<li class="navbar-item bm-nav-item <?= $isSupport ? 'bm-active': ''?>">
						<a class="bm-hr-nav-link" href="/supports">Support</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.support', ['isSupport' => $isSupport])
						</ul>
					</li>
					@endif
					@if (Gate::any(['sy_ad']))
					<li class="navbar-item bm-nav-item <?= $isLogs ? 'bm-active': ''?>">
						<a class="bm-hr-nav-link" href="/logs">Logs</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.logs', ['isLogs' => $isLogs])
						</ul>
					</li>
					@endif
					@if (Gate::any(['cr_ap', 'pt_as', 'co_cr', 'fi_vw', 'fi_ar', 'fi_ap', 'fi_cl']))
					<li class="navbar-item bm-nav-item <?= $isDataReporting ? 'bm-active': ''?>">
						<a class="bm-hr-nav-link" href="/data-reporting/">Account Summary</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.account-summary', ['isDataReporting' => $isDataReporting])
						</ul>
					</li>
					@endif
					@if (Gate::any(['us_cr', 'us_ch', 'us_vw', 'ro_cr', 'ro_ch', 'ro_vw']))
					<li class="navbar-item bm-nav-item <?= $isUser ? 'bm-active': ''?>">
						<a class="bm-hr-nav-link" href="/users">Users</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.users', ['isUser' => $isUser])
						</ul>
					</li>
					@endif
					@if (Gate::allows('po_sc') || Gate::allows('vp_sc') || Gate::allows('po_vm') || Gate::allows('qu_sc') || Gate::allows('bq_sc') || Gate::allows('qu_bm'))
					<li class="navbar-item bm-nav-item <?= $isTransaction  ? 'bm-active': ''?>">
						<a class="bm-hr-nav-link" href="/transactions">Transactions</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.transaction', ['isTransaction' => $isTransaction])
						</ul>
					</li>
					@endif
					@if (Gate::any(['cr_ap', 'cr_of', 'fi_ar']) || (Gate::allows('cr_sc') && $hasReadyBuyerCompany))
					<li class="navbar-item bm-nav-item <?= ($isCreditRequest || $isSiteVisit || $isCreditCheck) ? 'bm-active': ''?>">
						@if($userIsAR)
						<a class="bm-hr-nav-link" href="/credit/check">Credit</a>
						@else
						<a class="bm-hr-nav-link" href="/creditrequests">Credit</a>
						@endif
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.credit', [
							'isCreditRequest' => $isCreditRequest,
							'isSiteVisit' => $isSiteVisit,
							'isCreditCheck' => $isCreditCheck
							])
						</ul>
					</li>
					@endif
					@if (Gate::allows('vnXXXsc'))
					<li class="navbar-item bm-nav-item <?= $isVendor ? 'bm-active': ''?>"><a class="bm-hr-nav-link" href="/vendors">Vendors</a></li>
					@endif
					@if (Gate::any(['co_sc', 'vn_sc', 'co_cr']))
					<li class="navbar-item bm-nav-item <?= ($isPickupAddress || $isShippingAddress || $isCompany || $isVatExempt) ? 'bm-active': ''?>">
						@if(Gate::allows('co_cr') && $hasCompany)
							@if(!$userCompany->iscomplete)
								<a class="bm-hr-nav-link" href="/companies/{{ $userCompany->id }}">Company</a>
							@else
								<a class="bm-hr-nav-link" href="/companies/view/{{ $userCompany->id }}">Company</a>
							@endif
						@elseif(Gate::allows('co_cr'))
							<a class="bm-hr-nav-link" href="/companies/create">Company</a>
						@else
							<a class="bm-hr-nav-link" href="/companies">Company</a>
						@endif
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.company', [
							'hasCompany' => $hasCompany,
							'isCompany' => $isCompany,
							'buyerCompany' => $buyerCompany,
							'userCompany' => $userCompany,
							'isShippingAddress' => $isShippingAddress,
							'isPickupAddress' => $isPickupAddress,
							'isVatExempt' => $isVatExempt,
							'isMySuppliers' => $isMySuppliers
						])
						</ul>
					</li>
					@endif
					@if (Gate::allows('report_issue'))
					<li class="navbar-item bm-nav-item <?= ($isCustomerSupport) ? 'bm-active': '';?>">
						<a class="bm-hr-nav-link" href="/support/report-issue">Customer Support</a>
						<ul class="visible-xs list-unstyled sub-items">
						@include('layouts.side-menus.customer-support', ['isCustomerSupport' => $isCustomerSupport])
						</ul>
					</li>
					@endif
					@if (App\Module::find(5) && App\Module::find(5)->active)
						@if (Auth::user()->hasReadySupplierCompany() || Auth::user()->hasReadyBuyerCompany())
						<li class="navbar-item bm-nav-item <?= ($isChat) ? 'bm-active': '';?>">
							<a class="bm-hr-nav-link" href="/chat">Chat</a>
							<ul class="visible-xs list-unstyled sub-items">
							@include('layouts.side-menus.chat', ['isChat' => $isChat])
							</ul>
						</li>
						@endif
					@endif
					<li class="navbar-item bm-nav-item"><a class="logout-link" href="/logout">Sign out</a></li>
					<li class="hidden-xs">
						<a class="profile-link" href="/profile">
							<span class="profile-img" title="<?= Auth::user()->name ?>">
								<?php
									$fullname = explode(" ", Auth::user()->name);
									$firstName = reset($fullname);
									if (count($fullname) > 1) {
										$lastName = end($fullname);
									}
									$abbreviation = substr($firstName, 0, 1);
									if(isset($lastName))
										$abbreviation .= substr($lastName, 0, 1);
								?>
								<span class="name"><?= $abbreviation ?></span>
							</span>
						</a>
					</li>
				</ul>
			</div><!-- /.navbar-collapse -->
			@endif
		</div><!-- /.container-fluid --> 
	</nav>	
	@if (Auth::guest())		
		@yield('content')
	@else
	<section class="content">
		<div class="sidebar-wrapper hidden-xs">
			<div class="sidebar">
				@if (!$isChat)
					<ul class="sidebar-list {{ $isHome ? 'home' : '' }}">
				@endif
				@if ($isHome)
					@if(Gate::allows('co_cr') && Auth::user()->companypermissions(['co_cr'])->count() == 0)
						<li>
							<a class="bm-vr-nav-link" href="/companies/create">
								<span class="add-bold-icon round"></span>
								<span class="menu-link">Create Company</span>
							</a>
						</li>	
					@endif
					@if(Gate::allows('po_cr'))
						<li>
							<a class="bm-vr-nav-link" href="/purchaseorders/create">
								<span class="add-bold-icon round"></span>
								<span class="menu-link">Request To Buy</span>
							</a>
						</li>
					@endif
					@if(Gate::allows('qu_cr'))
						<li>
							<a class="bm-vr-nav-link" href="/quotations/create">
								<span class="add-bold-icon round"></span>
								<span class="menu-link">Request To Sell</span>
							</a>
						</li>
					@endif
					@if(Gate::denies('po_cr') && Gate::denies('qu_cr') && Gate::denies('co_cr'))
						<h3 class="bm-sidebar-h">Dashboard Menu</h3>
					@endif
				@else
					@include('layouts.side-menus.profile', ['isProfile' => $isProfile])
					@include('layouts.side-menus.company', [
							'hasCompany' => $hasCompany,
							'isCompany' => $isCompany,
							'buyerCompany' => $buyerCompany,
							'supplierCompany' => $supplierCompany,
							'userCompany' => $userCompany,
							'isShippingAddress' => $isShippingAddress,
							'isPickupAddress' => $isPickupAddress,
							'isVatExempt' => $isVatExempt,
							'isMySuppliers' => $isMySuppliers,
							'isMyBuyers' => $isMyBuyers
						])
					@include('layouts.side-menus.credit', [
						'isCreditRequest' => $isCreditRequest,
						'isSiteVisit' => $isSiteVisit,
						'isCreditCheck' => $isCreditCheck
					])
					@include('layouts.side-menus.transaction', ['isTransaction' => $isTransaction])
					@include('layouts.side-menus.users', ['isUser' => $isUser])
					@include('layouts.side-menus.support', ['isSupport' => $isSupport])
					@include('layouts.side-menus.customer-support', ['isCustomerSupport' => $isCustomerSupport])
					@include('layouts.side-menus.account-summary', ['isDataReporting' => $isDataReporting])
					@include('layouts.side-menus.chat', ['isChat' => $isChat])
					@include('layouts.side-menus.logs', ['isLogs' => $isLogs])
				@endif
				@if (!$isChat)
				</ul>
				@endif
			</div>
		</div>

		<div class="page-content">
				@yield('content')			
		</div>		
	</section>
	@endif
	</div>
	<script src="{{ mix('js/app.js') }}"></script>
	<script src="{{asset('js/owl.carousel.js')}}" ></script>
	<script src="{{asset('js/owl.carousel.min.js')}}" ></script>

	<script type="text/javascript">
		function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
	</script>
	@if (env('APP_URL')=='https://bizzmo.com')
		@include('layouts.analytics')
	@endif
	@stack('scripts')			
	</body>		
</html>