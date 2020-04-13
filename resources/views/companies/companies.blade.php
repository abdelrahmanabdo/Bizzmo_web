@extends('layouts.app' , ['hideRightMenuAndExtend' => true])
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
<div class="container col-sm-12">
    <div class="header-container">	<!-- row 1 -->
        <div class="title">Companies</div>

        <div class="right-container ">  <!-- column 2 -->
            <div class="search col-xs-12">
                <input type="text" name="" id="" placeholder="Search">
                <img class="search-icon" src="{{asset('images/search-icon.svg')}}"/>

            </div>
            <a href="/pickupaddresses/create" class="biz-button blank-bordered col-xs-12" id="lnksubmit">
                <img src="{{asset('images/add-colored.svg')}}" /> Join Company
            </a>
        </div>
    </div>	
    <div class="boxs-container col-sm-12 row">
        @foreach ($companies as $company)
        <div class="box col-xs-12 mt-1 col-sm-3 " >
            <div class="box-cover-container">
                @if(isset($company->companyProfile->cover))
                    <img src="{{asset($company->companyProfile->cover)}}"class="cover" alt="">
                @endif
            </div>            
            <div class="box-container col-sm-12" id="">
                @if(!isset($company->companyProfile->logo))
                <img class="logo" src="{{asset('images/company-logo-placeholder.png')}}" width="56" height="56" alt="">
                 @else
                <img class="logo" src="{{asset($company->companyProfile->logo)}}" width="56" height="56" alt="">
                 @endif
            <a href="{{url('/companies/view/'. $company->id )}}" class="title">{{$company->companyname}}</a>
                <span class="desc">{{$company->companyProfile->overview ?? ''}}</span>
                <div class="buttons col-xs-12">
                    <div class="button followButton-{{$company->id}} col-xs-8" onclick="followCompany('followButton','unfollowButton',{{$company->id}})">
                        <a href="#">
                            <img src="{{asset('images/add-icon.svg')}}" /> Follow
                        </a>
                    </div>
                    <div class="button unfollow unfollowButton-{{$company->id}} col-xs-8" hidden onclick="followCompany('unfollowButton','followButton',{{$company->id}})">
                        <a href="#">
                            <img src="{{asset('images/ok.svg')}}" /> Following
                        </a>
                    </div>                         
                    <a class="button col-xs-2" href="#">
                        <img class="leave"  src="{{asset('images/leave-arrow.svg')}}"  />
                    </a>
                </div>
            </div>
        </div>            
        @endforeach
    </div>
</div>
@endsection
<script>
    function followCompany(clicked,another,id){
        $('.'+clicked+'-'+id).toggle();
        $('.'+another+'-'+id).toggle();
    }
</script>