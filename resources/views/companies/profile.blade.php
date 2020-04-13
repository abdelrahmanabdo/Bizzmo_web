@extends('layouts.app')
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop   
@php    
    $countries    = \App\Country::whereActive('1')->pluck('countryname','id')->toArray();
@endphp
@section('content')
    @include('includes.company-profile-head')
    @if($mode == 'view' && Auth::user()->hasProfile())
        <div class="header-container col-sm-12">
            <h3 class="title"></h3>
            <div class="buttons"> 
            <a href="{{route('companies.profile.view' , ['id'=> Auth::user()->companies->first()->id ,'mode' => 'edit'])}}" class="biz-button blank-bordered ">
                    <img src="{{asset('images/edit-icon.svg')}}" /> {{ Auth::user()->hasProfile() ?  'Edit Profile' : 'Add Profile'}} 
                </a>
            </div>
        </div>	
        @include('includes.company-profile-overview')
        @include('includes.company-profile-contact-info')
    @else 
    <div class="header-container col-sm-12">
        <div class="title">
            @if(Auth::user()->hasProfile() )
            <a href="javascript:history.go(-1)" class="back-arrow">
                <img src="{{asset('images/arrow-left.svg')}}" />
            </a>
            @endif
            <div class="section-title">{{ Auth::user()->hasProfile() ?  'Edit Profile' : 'Add Profile'}} </div>
        </div>
    </div>  
      <div class="white-box col-sm-12">
        <div class="row">
            {{ Form::open(array('route' => array( 'companies.profile.edit' , $company->id) , 'method' => 'post' ,'enctype' => "multipart/form-data" , 'class'=>"tab-content")) }}
            @if(isset($company))
             {{Form::hidden('company_id' , $company->id)}}
            @endif 
            <div class="col-md-12">
                <div class="upload-image-container">
                    <div class="uploader coverContainer">
                        @isset($profile)
                        <img src="{{asset($profile->cover)}}" />
                     @endisset
                        {{Form::file('cover' ,array('class'=>'inputFile', 'id' => 'coverUploader'))}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="upload-image-container">
                    <div class="uploader logoContainer">
                        @isset($profile)
                           <img src="{{asset($profile->logo)}}" />
                        @endisset
                        {{Form::file('logo' ,array('class'=>'inputFile' , 'id' => 'logoUploader'))}}
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- productname --> 
                <div class="form-group text-input">
                    {{ Form::label('overview', 'overview', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::textarea('overview', $profile->overview, array('id' => 'name', 'class' => 'form-control')) }}
                    @else
                    {{ Form::textarea('overview', '', array('id' => 'product_name', 'class' => 'form-control' )) }}
                    @endif
                    @if ($errors->has('product_name')) <p class="bg-danger">
                        {{ $errors->first('product_name') }}</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input">
                    {{ Form::label('Country', 'Country', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::select('country', $countries, $profile->country?? '', array('id' => 'select_country', 'class' => 'form-control bm-select' , 'placeholder' => 'Country','style' => 'width: 100%')) }}
                    @else
                    {{ Form::select('country', $countries, '', array('id' => 'select_country', 'class' => 'form-control bm-select' , 'placeholder' => 'Country','style' => 'width: 100%')) }}
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('city', 'City', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::select('city',[],$profile->city ?? '', array('id' => 'select_city', 'class' => 'form-control bm-select' , 'placeholder' => 'City','style' => 'width: 100%')) }}
                    @else
                    {{ Form::select('city', [],'', array('id' => 'select_city', 'class' => 'form-control bm-select' , 'placeholder' => 'City','style' => 'width: 100%')) }}
                    @endif
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'Full Address', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::text('address', $profile->address ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::text('address', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'E-mail', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::email('email', $profile->email ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::email('email', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'PO Box', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::text('pobox', $profile->pobox ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::text('pobox', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'Tel', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::text('tel', $profile->tel ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::text('tel', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'Fax', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::text('fax', $profile->fax ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::text('fax', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('product_', 'Employees number', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::number('employees_number', $profile->employees_number?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::number('employees_number', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                    {{ Form::label('number', 'Customers number', array('class' => 'form-label')) }}
                    @if(isset($profile))
                    {{ Form::number('customers_number', $profile->customers_number ?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @else
                    {{ Form::number('customers_number', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                    @endif
                </div>
            </div>

            <div class="row col-md-12" style="margin:20px  0px !important ; padding : 0 !important">
                <div class="col-md-8">
                    <a href="{{url('/companies/profile/'. $company->id )}}" class="biz-button  blank-bordered">Cancel</a>
                </div>
                <div class="col-md-4">
                    {{ Form::submit( Auth::user()->hasProfile() ?  'Update Profile' : 'Save' , array( 'class' =>'biz-button  btn-create colored-default')) }}
                </div>
            </div>
            {{ Form::close() }}

        </div>
      </div>  
    @endif
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        @if(isset($profile))
          $('.text-input').addClass('focused');
          $('.select2-selection.select2-selection--single').addClass('focused');
          $('#coverContainer').css("background-image", "url({{asset($profile->logo)}})");
          $('#logoContainer').css("background-image", "url({{asset($profile->cover)}})");
        @endif


        var country =  $('#select_country').val();
        var selectedCity =  $('#select_city').val();
            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        if(selectedCity == index)
                        {
                            $('#select_city').append($('<option></option>').val(index).html(city).attr("selected", true));
                        }else{
                            $('#select_city').append($('<option></option>').val(index).html(city));
                        }
                    });
                },
            });

        $('#select_country').on('change', function () {
            var country = $(this).val();

            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        var newOption = new Option(city, index, false, false);
                        $('#select_city').append(newOption).trigger('change');
                    });
                },
            });
        });
    });
</script>
@endpush
<style>
    .content .tab-content {
        margin: 24px !important;
    }
    .tab-content {
        padding: 24px 0px !important;
    }
</style>