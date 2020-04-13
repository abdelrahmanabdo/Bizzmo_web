<html>
<head>
	<title>{{ config('app.companyname') }}</title>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="HandheldFriendly" content="true">
	<meta name="description" content="To the new era of business growth" />
	<meta name="twitter:card" value="summary">
	<meta property="og:title" content="Bizzmo | Extend your business"/>
	<meta property="og:site_name" content="Bizzmo"/>
	<meta property="og:image" content="{{ asset('images/bizzmo-logo.png') }}"/>
	<meta property="og:url" content="{{ URL::to('/') }}" />
	<meta property="og:description" content="Bizzmo, to the new era of business growth" />
	<link rel="shortcut icon" href="{{ asset('images/bizzmo-logo.png') }}" type="image/x-icon">
	<link rel="icon" href="{{ asset('images/bizzmo-logo.png') }}" type="image/x-icon">
	<link rel="stylesheet" href="{{asset('css/owl.carousel.min.css')}}" />
	<link rel="stylesheet" href="{{asset('css/owl.carousel.css')}}" />
	{{-- <link href="{{asset('css/all.css')}}" rel="stylesheet"> --}}
	{{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">

	<link href="{{ mix('css/app.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Open+Sans:400,600" rel="stylesheet">
	
	<style>
	body {
		padding-top: 70px;
	}
	</style>
	@yield('styles')
</head>
<body >
	<div id="app">
	@if (\Request::is(['chat', 'chat/*']))
	@else
		<div></div>
	@endif
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
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid row col-md-12 navbar-header">
			<!-- Brand and toggle get grouped for better mobile display -->
				@if (!Auth::guest())
				<button type="button" class="navbar-toggle collapsed hidden-lg col-sm-12" data-toggle="collapse" data-target=".menu-collapse" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				@endif
			<div class=" col-md-2 col-xs-12">
				<div class="flex-container" style="justify-content : center !important">
					<a href="/" style="margin: auto 0"> 
						<img src="{{ asset('images/bizzmo-logo.png') }}" class="pull-left logo">
					</a>
					<a class="navbar-brand visible-lg" href="/">Bizzmo</a>
				</div>
			</div>
			@auth
			<div class="col-md-8 col-xs-12 middle-section" id="">
				<div class="search-bar col-md-8 col-xs-12">
					<div class="search-input col-md-8 col-md-12">
						<input type="text" placeholder="Search products, rfq, offers….." class="input-field" id="searchQuery"/>
						<img class="search-icon" src="{{asset('images/search-icon.svg')}}"/>
					</div>
				</div>
				<div class="search-results col-md-8 col-md-offset-2 col-xs-12 " hidden>
					{{-- Products --}}
					<div class="result" id="productsBlock">
						<div class="result-header">
							<div class="title">Products</div>
							<div class="see-more"><a href="#">See more </a></div>
						</div>
						<div class="result-content" id="productsResult">
						</div>

					</div>
					{{--- People --}}  
					<div class="result" id="peopleBlock">
						<div class="result-header">
							<div class="title">People</div>
							<div class="see-more"><a href="#">See more </a></div>
						</div>
						<div class="result-content" id="peopleResult">
						</div>
					</div>
					{{--- Companies --}}  
					<div class="result"  id="companiesBlock">
						<div class="result-header">
							<div class="title">Companies</div>
							<div class="see-more"><a href="#">See more </a></div>
						</div>
						<div class="result-content" id="companiesResult">
						</div>
					</div>
					{{-- Footer
					<div class="search-footer">
						<a href="">See all results for s</a>
					</div> --}}
					<div class="no-results" hidden>
						<span>No Results</span>
					</div>
				</div>
			</div>
			<div class="right-section col-md-2 col-xs-12">
				<div class="collapse navbar-collapse col-md-3 menu-collapse" id="">
					<ul class="nav navbar-nav navbar-right">
						<li class="hidden-xs">
							<a class="profile-link" href="/profile">
								<span class="profile-img" title="<?= Auth::user()->name ?>">
									@if(isset(\Auth::user()->getProfile()->logo)) 
									<img class="profile-img" style="border-radius : 50%" src="{{url(\Auth::user()->getProfile()->logo)}}"/>
									@else
									<img class="profile-img" style="border-radius : 50%" src="{{asset('images/company-logo-placeholder.png')}}"/>

									@endif
								</span>
							</a>
						</li>
						<li class="navbar-item bm-nav-item">
							<span style="margin :0px 10px">{{explode(" ", Auth::user()->name)[0]}}</span> <img id="dropdown-arrow" src="{{asset('images/angel-down.svg')}}" />
							<div class="biz-dropdown">
								<a href="/logout">Logout</a>
							</div>
						</li>

						<li class="navbar-item notification-icon">
							<img src="{{asset('images/notification-icon.svg')}}"/>
						</li>
						<li class="navbar-item cart-icon" id="showCompanyInquiry">                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
							<a href="#">
								<img src="{{asset('images/cart-icon.svg')}}"/>
								<div class="cart-number"><span>{{\App\Inquiry::where('buyer_id',\Auth::user()->getCompanyId())->count()}}</span></div>
							</a>
						</li>
						@php
							$inquiry = \App\Inquiry::with(['product','product.productcategory' , 'product.currency' , 'product.images'])
													->where('buyer_id', \Auth::user()->getCompanyId())												
													->get()
													->groupBy('deal_id');

							$subtoal = \App\Inquiry::where(['status'=>'waiting' , 'buyer_id'=>\Auth::user()->getCompanyId()])
													->sum(DB::raw('(price - discount_value) * qty '));
						@endphp

						<div id="companyInquiry" class="company-inquiry" style="display : none">
							@if($inquiry->count() > 0)
								@foreach ($inquiry as  $deal_id =>$deal)
								<div class="inquiry-header">
									<div class="deal-head">	
										<div class="deal-number">Deal {{$loop->iteration}}</div>
										<div class="deal-id">Deal id {{$deal_id}}</div>
									</div>  									         
									<div class="deal-text">Metra compnay</div>
								</div>
						    	@forelse ($deal as  $item)
									<div class="inquiry-items">
										<div class="item">
											<div class="left">
												<div class="item-image">
												<img src="{{asset($item->product->images[0]->image ?? '')}}"  width="70" height="60"/>
													</div>
													<div class="item-details">
														<div class="details-category">
															{{$item->product->productcategory->category ?? ''}}
														</div>
															<a class="details-name" href="{{asset('/companies/product/'.$item->product->id)}}" >{{$item->product->name}}</a>
															<span class="details-qty"  >QTY :  {{$item->qty}} Pieces</span>
													</div>
											</div>
											<div class="item-price">
												<div class="price-all">{{( $item->discount ?  ($item->price  - $item->discount_value) : $item->price) * $item->qty .' '. $item->product->currency->abbreviation ?? ''}}</div>
												<div class="price-offer" >{{$item->discount  ?  $item->price * $item->qty   .' '. $item->product->currency->abbreviation : '' }}</div>
											</div>
										</div>
									</div>
								@empty 

								@endforelse
									@endforeach
								<div class="inquiry-subtotal">
									<div class="title">Subtotal</div>
								<div class="total-price">{{$subtoal}} $</div>
								</div>
								<div class="inquiry-buttons">
								<a href="/chat/negotiate" class="negotiateButton biz-button blank-bordered col-md-5">Negotiate</a>
									<a href="#" class="checkoutButton biz-button colored-default col-md-5">Checkout</a>
								</div>
							@else
							 <h4 style="display:flex;align-self : center ;margin-top:30px">Empty Cart</h4>
							@endif
						</div>
					</ul>
				</div><!-- /.navbar-collapse -->
			</div>

			@endauth
		</div><!-- /.container-fluid --> 
	</nav>	
	@if (Auth::guest())		
		@yield('content')
	@else
	<section class="content row">
		<div class=" col-md-2 col-xs-12" style="padding : 0">
		<div class="sidebar-wrapper" @if(isset($hideLeftMenu)) hidden @endif >
			<div class="left-section sidebar" > 
					<div class="company-logo">
					@if(isset(\Auth::user()->getProfile()->logo))
						<img class="logo" src="{{url(\Auth::user()->getProfile()->logo)}}"/>
					@else
						<img src="{{asset('images/company-logo-placeholder.png')}}" width="60" height="60" alt="">
					@endif
					</div>
					@if(\Auth::user()->getCompanyName())
					<div class="company-info col-md-12">
						<div class="company-name">{{\Auth::user()->getCompanyName()}}</div>
						{{-- <div class="company-specialization">Comapny Specialization</div> --}}
					</div>
					@endif
					{{-- <div class="company-statistics col-md-12">
						<div class="section">
						<div class="icon"><img src="{{asset('images/connections-icon.svg')}}" /></div>
							<div class="text">Connections</div>
							<div class="number">40</div>
						</div>
						<div class="section">
							<div class="icon"><img src="{{asset('images/followers-icon.svg')}}" /></div>
							<div class="text">Followers</div>
							<div class="number">122</div>
						</div>
						<div class="section">
							<div class="icon"><img src="{{asset('images/following-icon.svg')}}" /></div>
							<div class="text">Following</div>
							<div class="number">36</div>
						</div>
					</div> --}}
					@if(Gate::allows('po_cr') || Gate::allows('qu_cr'))
					<div class="company-buttons col-md-12">
						@if(Gate::allows('po_cr'))
						<a class="biz-button blank-bordered col-md-10" href="/purchaseorders/create">
								Request To Buy
						</a>
						@endif
						@if(Gate::allows('qu_cr'))
						<a class="biz-button blank-bordered col-md-10" href="/quotations/create">
								Request To Sell
						</a>
						@endif					
					</div>
					@endif
					<div class="navigation-buttons-section col-md-12">
						@if (Gate::any(['cr_ap', 'pt_as', 'co_cr', 'fi_vw', 'fi_ar', 'fi_ap', 'fi_cl']))
						<a  href="{{url('data-reporting/statement-of-account')}}" class="navigation-button
						 @if(\Request::is('data-reporting/statement-of-account') ||
						 	 \Request::is('data-reporting/statement-of-account/*') ||
							 \Request::is('data-reporting/outstanding') ||
							 \Request::is('data-reporting/outstanding/*')) 
						 active
						 @endif
						  col-md-12">
							<div class="icon"><img src="{{asset('images/summary-icon.svg')}}" /></div>
							<div class="text">Account Summary</div>
						</a>
						@endif
						@if (App\Module::find(5) && App\Module::find(5)->active)
						@if (Auth::user()->hasReadySupplierCompany() || Auth::user()->hasReadyBuyerCompany())
						<a href="{{url('chat')}}" class="navigation-button @if(\Request::is('chat')) active @endif col-md-12">
							<div class="icon"><img src="{{asset('images/messages-icon.svg')}}" /></div>
							<div  class="text">Messages</div>
						</a>
						@endif
						@endif

						@if (Gate::allows('po_sc') || Gate::allows('vp_sc') || Gate::allows('po_vm') || Gate::allows('qu_sc') || Gate::allows('bq_sc') || Gate::allows('qu_bm'))
						<a href="{{url('/transactions')}}" class="navigation-button @if(\Request::is('transactions') || \Request::is('purchaseorders') || 
								 	 \Request::is('quotations/create') ||	\Request::is('quotations')) active @endif  col-md-12">
							<div class="icon"><img src="{{asset('images/transactions-icon.svg')}}"/></div>
							<div  class="text">Transactions</div>
						</a>
						@endif
						<div 
						class="navigation-button 
							@if(\Request::is('companies/*') || \Request::is(['shippingaddresses', 'shippingaddresses/*']) ||
							\Request::is(['pickupaddresses', 'pickupaddresses/*'])  ||\Request::is('forwarder/inspection/template/*') ||
							 \Request::is('forwarder/route/template/*') || \Request::is('forwarder/services/create/*')) active @endif	
						col-md-12">
							<div class="icon"><img src="{{asset('images/companies-icon.svg')}}" /></div>
							@if(Gate::allows('co_cr') && $hasCompany)
								@if(!$userCompany->iscomplete)
								<a href="{{url('/companies/'. $userCompany->id )}}" class="text
								">Companies</a>
								@else
								<a href="{{url('/companies/view/'. $userCompany->id )}}" class="text">My Company</a>
								@endif
							@elseif(Gate::allows('co_cr'))
								<a href="{{url('/companies/create')}}" class="text">My Company</a>
							@else
								<a href="{{url('/companies')}}" class="text">My Company</a>
							@endif
						</div>
						<div class="navigation-button @if(\Request::is('list/companies')) active @endif	col-md-12">	
							<div class="icon"><img src="{{asset('images/companies-icon.svg')}}" /></div>
							<a href="{{url('/list/companies')}}" class="text">Companies</a>
						</div>
						<a href="/users" class="navigation-button @if(\Request::is('users*')) active @endif  col-md-12">
							<div class="icon"><img src="{{asset('images/connections-icon.svg')}}" /></div>
							<div class="text">Users</div>
						</a>
					<a href="{{url('support/report-issue')}}" class="navigation-button @if(\Request::is('support/*')) active @endif col-md-12">
							<div class="icon"><img src="{{asset('images/news-feed-icon.svg')}}" /></div>
							<div class="text">Customer Support</div>
						</a>
					</div>
			</div>
		</div>
		</div>
		<div class="page-content @if(isset($hideRightMenuAndExtend)) col-md-10 @else col-md-8 @endif col-xs-12">
				@yield('content')				
		</div>	
		<div class="right-sidebar col-md-2 col-xs-12" style="float : right" @if(isset($hideRightMenu) || isset($hideRightMenuAndExtend)) hidden @endif>
			<div class="header-text">
				Latest activities
			</div>
			<div class="activities">
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
				<div class="avtivity">
					<div class="activity-user-image">
						<img src="{{asset('images/company-logo-placeholder.png')}}" />
					</div>
					<div class="activity-content">
						<div class="text">Ahmed Ali commented on Ahmed 3la2 post</div>
					<div class="time"> <img src="{{asset('images/time-icon.svg')}}"/>3 hours agp</div>
					</div>
				</div>
			</div>

		</div>	

	</section>

	@endif
	</div>
	<script src="{{ mix('js/app.js') }}"></script>
	<script src="{{asset('js/owl.carousel.js')}}" ></script>
	<script src="{{asset('js/owl.carousel.min.js')}}" ></script>
	{{-- <script src="{{asset('js/all.js')}}"></script> --}}
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.js"></script>
	<script type="text/javascript">
		function isNumber(n) {
			return !isNaN(parseFloat(n)) && isFinite(n);
		}
		
	</script>
	@if (env('APP_URL')=='https://bizzmo.com')
		@include('layouts.analytics')
	@endif
	@stack('scripts')
</div>			
	</body>		
</html>

