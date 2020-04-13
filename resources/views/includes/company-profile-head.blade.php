<div class="head-container col-md-12 col-xs-12">
    <div class="cover-container">
        @if(isset(\Auth::user()->getProfile()->cover))
            <img src="{{asset(\Auth::user()->getProfile()->cover)}}"class="cover" alt="">
        @endif
    </div>
    <div class="info-container col-md-12 col-xs-12">
        <div class="logo-box col-md-2 col-xs-2">
            @if(!isset(\Auth::user()->getProfile()->logo))
                <img src="{{asset('images/company-logo-placeholder.png')}}" width="60" height="60" alt="">
            @else
                <img src="{{asset(\Auth::user()->getProfile()->logo)}}" width="60" height="60" alt="">
            @endif
        </div>
        <div class="info col-md-10 col-xs-10">
            <div class="top-section col-md-12 col-xs-12">
                <div class="name">
                {{\Auth::user()->getCompanyName()}}
                </div>
    
            </div>
            <div class="middle-section col-md-12 col-xs-12">
                {{\Auth::user()->getProfile()->overview ?? ''}}
            </div>
            @if( \Auth::user()->getCompanyIndustries()->count() > 0)
            <div class="below-section col-xs-12 col-md-12 ">
                @if(\Auth::user()->getCompanyIndustries()->count() <= 5)
                 @foreach (\Auth::user()->getCompanyIndustries() as  $industry)
                  <div class="single-category col-xs-3"><span>{{$industry->name}}</span></div>
                 @endforeach
                @else
                    @foreach (\Auth::user()->getCompanyIndustries() as  $industry)
                     @if($loop->iteration <=5 )
                        <div class="single-category col-xs-3"><span>{{$industry->name}}</span></div>
                     @endif
                    @endforeach
                    <div class="single-category col-xs-3"><span>+{{\Auth::user()->getCompanyIndustries()->count() - 5}}</span></div>
                @endif
            </div>
            @endif
        </div>
    </div>
    <div class="nav col-md-12 col-xs-12">   
        @include('includes.companies-nav')
    </div>
</div>