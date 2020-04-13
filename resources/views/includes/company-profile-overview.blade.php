<div class="profile-overview-container col-md-12 col-xs-12">
    <div class="overview col-md-12 col-xs-12">
        <div class="overview-title">Overview</div>
        <div class="overview-content">  
            {{$company->companyProfile->overview ?? ''}}
        </div>
    </div>
    <div class="numbers col-md-12 col-xs-12">
        <div class="numbers-employess">
            <div class="title">Employess Number :</div>
            <div class="number">{{\Auth::user()->getProfile()->employees_number ?? 0 }} employees</div>
        </div>
        <div class="numbers-customers">
            <div class="title">Customers Number :</div>
            <div class="number">{{\Auth::user()->getProfile()->customers_number ?? 0 }} customers</div>
        </div>
    </div>

    <div class="product-categories col-md-12 col-xs-12">
        <div class="title">Product Categories Interests</div>
        <div class="categories">
            @foreach ($company->industries as  $industry)
            <div class="single-category col-xs-3"><span>{{$industry->name}}</span></div>
          @endforeach
        </div>
    </div>
</div>