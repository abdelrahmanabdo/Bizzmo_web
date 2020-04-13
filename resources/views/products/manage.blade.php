@extends('layouts.app' , ['hideRightMenu' => true , 'hideLeftMenu' => true])
@section('title')
@if (isset($title))
{{ $title }}
@endif
@endsection

@section('headmeta')
<style type="text/css">

</style>
@endsection
<?php
    $categories = \App\Productcategory::pluck('category','id')->toArray();
    $brands     = \App\Brand::whereActive('1')->pluck('name' , 'id')->toArray();
    $currency    = \App\Currency::whereActive('1')->pluck('abbreviation' , 'id')->toArray();
    $countries    = \App\Country::whereActive('1')->pluck('countryname','id')->toArray();
?>
@section('content')
<div class="page-layout">
    <div class="form-container">
        <div class="row  form-header-container">
            <a href="javascript:history.go(-1)" class="back-arrow">
                <img src="{{asset('images/arrow-left.svg')}}" />
            </a>
            <div class="form-header-title-container">
                @if(isset($product))
                <h2 class="form-header-title">{{$product->name}}</h2>
                {{-- <h4 calss="form-header-hint">{{$product->company->companyname}}&nbsp;&nbsp;&nbsp;&nbsp;</h4> --}}
                @else
                <h2 class="form-header-title">Create New Product</h2>
                <h4 class="form-header-hint"> You can easily add new products to your product catalog .</h4>
                @endif
            </div>
        </div>
        <div class="row tab-progress">
            <div class="checkout-wrap col-md-12  col-sm-12 col-xs-10">
                <ul class="checkout-bar">
                    <li class="active"><a href="#get-started" data-toggle="tab">1 - Basic Information</a>
                    </li>
                    <li class=""><a href="#about-you" data-toggle="tab">2 - Specifications</a>
                    </li>
                    <li class=""><a href="#looking-for" data-toggle="tab">3 - Upload Pictures</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tabbable-panel">
            <div class="row">
                @if(isset($product))
                {{ Form::open(array('route' => 'updateProductDataPosting' , 'method' => 'post' ,'enctype' => "multipart/form-data" , 'class'=>"tab-content")) }}
                @else
                {{ Form::open(array('route' => 'postProductData' , 'method' => 'post' ,'enctype' => "multipart/form-data" , 'class'=>"tab-content")) }}
                @endif
                {{--Basic information--}}
                <div class="tab-pane active row" id="get-started" style="margin: 0 !important;">
                    {{-- <form method="POST" action="{{route('postProductData')}}" > --}}
                    <div class="row">
                        <div class=" TabHeaderTitle row col-md-12  ">
                            <h2 class="TabTitle">Basic Information</h2>
                            <h4 class="TabHint"> You can add different information to your Product so the user can pick
                                suitable
                                option for him.</h4>
                        </div>
                            <div class="col-md-12">
                            <!-- productname -->
                            @if(isset($product))
                            {{Form::hidden('id' , $product->id)}}
                            @endif
                            <div class="form-group text-input">
                                {{ Form::label('product_name', 'Product Name', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('product_name',old('name', $product->name), array('id' => 'name', 'class' => 'form-control')) }}
                                @else
                                {{ Form::text('product_name', '', array('id' => 'product_name', 'class' => 'form-control' )) }}
                                @endif
                                @if ($errors->has('product_name')) <p class="bg-danger">
                                    {{ $errors->first('product_name') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input">
                                {{ Form::label('product_name', 'Category ', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::select('product_category', $categories,$product->category_id ,array( 'id' => "product_category",'class' => 'form-control' ,'style' => 'width: 100%' )) }}
                                @else
                                {{-- <input name="selected_product_category" id="selected_product_category" type="hidden"
                            class="form-control" value="{{$categoryId}}">
                                <input name="selected_category_name" id="selected_category_name" type="hidden"
                                    class="form-control" value="{{$categoryName}}"> --}}
                                {{ Form::select('product_category', $categories  , '',array( 'id' => "product_category",'class' => '  select-input form-control ' ,'placeholder' => '','style' => 'width: 100%' )) }}
                                @if ($errors->has('product_category')) <p class="bg-danger">
                                    {{ $errors->first('product_category') }}</p>
                                @endif
                                @endif
                            </div> <!-- category -->
                        </div> <!-- select Category end -->
                        <div class="col-md-6">
                            <div class="form-group text-input">
                                {{ Form::label('product_name','Sub Category', array('class' => 'form-label')) }}
                                
                                @if(isset($product))
                                {{ Form::select('product_subCategory',[],  '',array( 'id' => "product_subCategory",'class' => 'form-control' ,'style' => 'width: 100%' )) }}

                                @else
                                {{-- <input name="selected_product_category" id="selected_product_category" type="hidden"
                            class="form-control" value="{{$categoryId}}">
                                <input name="selected_category_name" id="selected_category_name" type="hidden"
                                    class="form-control" value="{{$categoryName}}"> --}}
                                {{ Form::select('product_subCategory',[],  '',array( 'id' => "product_subCategory",'class' => 'form-control' ,'placeholder' => '','style' => 'width: 100%' )) }}
                                @endif
                                @if ($errors->has('product_subCategory')) <p class="bg-danger">
                                    {{ $errors->first('product_subCategory') }}</p>
                                @endif
                            </div> <!-- category -->
                        </div> <!-- column 3 end -->

                        <div class="col-md-12">
                            <div class="form-group text-input required">
                                {{ Form::label('product_description', 'Product description', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::textarea('product_description', $product->description, array('id' => 'product_description', 'class' => 'form-control')) }}
                                @else
                                {{ Form::textarea('product_description', old('product_description'), array('id' => 'product_description', 'class' => 'form-control')) }}
                                @endif
                                @if ($errors->has('product_description')) <p class="bg-danger">
                                    {{ $errors->first('product_description') }}</p>
                                @endif
                            </div>
                        </div> <!-- description end -->
                        <div class="col-md-12">
                            <div class="form-group text-input">
                                {{ Form::label('product_name', 'Status ', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::select('product_status',['refurbished'=>'Refurbished' , 'used' => 'Used'],$product->condition,array( 'class' => 'form-control' ,'style' => 'width: 100%' )) }}
                                @else
                                {{ Form::select('product_status',['refurbished'=>'Refurbished' , 'used' => 'Used'],  '',array( 'id' => "product_status",'class' => 'form-control' ,'style' => 'width: 100%' , 'placeholder' => 'Status' )) }}
                                @endif
                                @if ($errors->has('product_status')) <p class="bg-danger">
                                    {{ $errors->first('product_status') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 price-conteiner">
                            <div class="price-text col-md-12">
                                {{ Form::label('Price' ,'', array('class' => 'text  col-md-12' ,'style' => 'color: #1a425e; font-weight: 600')) }}
                                <p class="col-md-12 price-small-text" >You can skip adding the price offer if you’re haven’t offers for
                                    this
                                    product .</p>

                            </div>
                            <div class="price-fields  col-md-12">
                                <div class=" text-input col-md-6">
                                    <div class="col-md-4 text-input">
                                        <div >
                                            {{ Form::label('product_name', 'Currency', array('class' => 'form-label')) }}
                                            
                                            @if(isset($product))
                                            {{ Form::select('product_currency', $currency, $product->currency_id,array( 'id' => "product_currency",'class' => 'form-control' ,'style' => 'width: 100%'  )) }}
                                            @else
                                            {{ Form::select('product_currency', $currency,  '',array( 'id' => "currency",'class' => 'form-control' ,'placeholder' => '','style' => 'width: 100%'  )) }}
                                            @endif
                                        </div>
                                        @if ($errors->has('product_currency'))
                                        <p class="bg-danger">
                                            {{ $errors->first('product_currency') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group text-input required">
                                            {{ Form::label('', 'Product Price', array('class' => 'form-label')) }}
                                            @if(isset($product))
                                            {{ Form::text('product_price', $product->price, array('id' => 'product_price', 'class' => 'form-control' )) }}
                                            @else
                                            {{ Form::text('product_price', old('product_price'), array('id' => 'product_price', 'class' => 'form-control' )) }}
                                            @endif
                                        </div>
                                        @if ($errors->has('product_price')) <p class="bg-danger">
                                            {{ $errors->first('product_price') }}</p>
                                        @endif
                                    </div> <!-- productname end -->
                                </div>
                                <div class=" col-md-3" style="display:flex">
                                    <input type="checkbox" id="showOfffer" class="form-control " name="viewPriceOffer"
                                        onchange="show_price_offer()" />
                                    {{ Form::label('showPriceOffer', 'Add Price Offer', array('class' => 'bm-label ' , 'style'=> 'align-self :center')) }}
                                </div>
                                <div class=" col-md-3 priceOffer @if(!isset($product)) hidden @endif">
                                    <div class="col-md-12">
                                        <div class="form-group text-input required">
                                            {{ Form::label('product_price_offer', 'Price Offer', array('class' => 'form-label')) }}
                                            @if(isset($product))
                                            {{ Form::text('product_price_offer', $product->offer ?? '', array('id' => 'product_price_offer', 'class' => 'form-control' )) }}
                                            @else
                                            {{ Form::text('product_price_offer', old('product_price_offer'), array('id' => 'product_price_offer', 'class' => 'form-control' )) }}
                                            @endif
                                        </div>
                                    </div> <!-- productname end -->
                                </div> <!-- catego  ry -->
                            </div>
                        </div>

                        <div class="row col-md-12" style="margin:20px  0px !important ; padding : 0 !important">
                            <div class="col-md-8">
                                <div class="biz-button blank-bordered  btn-cancel">Cancel</div>
                            </div>
                            <div class="col-md-4">
                                <a class="biz-button colored-default btn-next " href="#about-you" data-toggle="tab">Next</a>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Specifications Tab --}}
                <div class="tab-pane" id="about-you">
                    <div class="tabHeader col-md-12 row">
                        <div class=" TabHeaderTitle row col-offset-4 ">
                            <h2 class="TabTitle">Product Specification</h2>
                            <h4 class="TabHint"> Product stock status like quantity, matrial, brand, Model Sizes, etc.
                            </h4>
                        </div>
                        <div class="row new-attribute-container">
                            <div id="new-cust-field biz-button blank-bordered" style="padding : 5px 16px">
                                <a href="javascript:void(0);" class="add-field" data-popover-content="#popFields"
                                    data-toggle="popover" data-placement="bottom">
                                    <span class="product-form-popup"> 
                                        <img
                                            src="{{asset('images/add-background-color.svg')}}" /> Add
                                        Custom Field </span>
                                </a>
                            </div>
                            <div class="row col-md-12 col-sm-12">
                                    <!-- Content for Popover #1 -->
                                    <div class="hidden" id="popFields">
                                        <div class="popover-body">
                                            <div style="justify-content : center">
                                                <input type="text" placeholder="Search" id="txtAttributeSearch"
                                                    autocomplete="off">
                                                <div></div>
    
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>

                    </div>
                    <div class="row" id="product-details-section">
                        <div class="col-md-6 ">
                            <div class="form-group text-input required">
                                {{ Form::label('product_', 'Product Line', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_product_line',$product->attributes[0]->pivot->value ?? '', array('id' => 'product_line', 'class' => 'form-control' )) }}
                                @else
                                {{ Form::text('attribute_product_line','', array('id' => 'product_line', 'class' => 'form-control' )) }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input">
                                {{ Form::label('brand', 'Brand', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::select('attribute_brand',$brands ,$product->attributes[1]->pivot->value?? '',array( 'id' => "attribute_brand",'class' => 'form-control' ,'style' => 'width: 100%' )) }}
                                @else
                                {{ Form::select('attribute_brand',$brands , '',array( 'id' => "brands",'class' => 'form-control' ,'placeholder' => '','style' => 'width: 100%' )) }}
                                @endif
                                @if ($errors->has('brand')) <p class="bg-danger">
                                    {{ $errors->first('brand') }}</p> @endif
                            </div> <!-- category -->
                        </div>
                        <!-- column 3 end -->
                        <div class="col-md-6">
                            <!-- productname -->
                            <div class="form-group text-input required">
                                {{ Form::label('product_', 'Product Material', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_material',$product->attributes[2]->pivot->value?? '', array( 'class' => 'form-control' )) }}
                                @else
                                {{ Form::text('attribute_material','', array( 'class' => 'form-control' )) }}
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input required">
                                {{ Form::label('product_', 'Product Model', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_model',$product->attributes[3]->pivot->value?? '', array('id' => 'product_model', 'class' => 'form-control' ) ) }}
                                @else
                                {{ Form::text('attribute_model','', array('id' => 'product_model', 'class' => 'form-control' ) ) }}
                                @endif

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('product_', 'Product Weight', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_weight',$product->attributes[4]->pivot->value?? '', array( 'id' => 'attribute_product_weight' , 'class' => 'form-control ' )) }}
                                @else
                                {{ Form::text('attribute_weight','', array( 'id' => 'attribute_product_weight' , 'class' => 'form-control ' )) }}
                                @endif

                                @if ($errors->has('weight')) <p class="bg-danger">
                                    {{ $errors->first('weight') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('unit', 'Unit', array('class' => 'form-label')) }}
                                {{ Form::select('weight_unit',array_merge( ['KG' => 'KG' , 'lbs' => 'lbs']),'', array( 'id' => 'attribute_product_weight_unit' , 'class' => 'form-control  bm-select ' ,'placeholder' => '','style' => 'width: 100%')) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('product_', 'Product Warranty', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_warranty',$product->attributes[5]->pivot->value?? '', array(  'class' => 'form-control ' )) }}
                                @else
                                {{ Form::text('attribute_warranty','', array(  'class' => 'form-control ' )) }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('product_', 'Period', array('class' => 'form-label')) }}
                                {{ Form::select('warranty_period', ['Month'=>'Months' ,'Year' => 'Years'] ,'', array( 'id' => 'attribute_product_period' , 'class' => 'form-control  bm-select ' ,'placeholder' => "" ,'style' => 'width: 100%')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                                {{ Form::label('product_', 'UOM', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_UOM',$product->attributes[6]->pivot->value?? '', array('id' => 'attribute_product_uom', 'class' => 'form-control' )) }}
                                @else
                                {{ Form::text('attribute_UOM', '', array('id' => 'attribute_product_uom', 'class' => 'form-control' )) }}
                                @endif

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                                {{ Form::label('product_', 'Product Manufacturer', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_manufacturer',$product->attributes[7]->pivot->value?? '', array('id' => 'attribute_product_manufacturer', 'class' => 'form-control' )) }}
                                @else
                                {{ Form::text('attribute_manufacturer', '', array('id' => 'attribute_product_manufacturer', 'class' => 'form-control' )) }}
                                @endif

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('product_', 'Volume', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_volume',$product->attributes[8]->pivot->value?? '', array( 'class' => 'form-control  ' )) }}
                                @else
                                {{ Form::text('attribute_volume','', array( 'class' => 'form-control  ' )) }}
                                @endif
                                @if ($errors->has('product_volume')) <p class="bg-danger">
                                    {{ $errors->first('product_volume') }}</p> @endif
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group text-input">
                                {{ Form::label('volume', 'Volume', array('class' => 'form-label')) }}
                                {{ Form::select('volume_unit', ['cubic metre'=>'cubic metre' , 'litre'=>'litre'] ,'', array( 'id' => 'attribute_product_volume_unit' , 'class' => 'form-control  bm-select ', 'placeholder' => '' ,'style' => 'width: 100%')) }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input">
                                {{ Form::label('Country_of_rigin', 'Country Of Origin', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::select('attribute_country_of_origin', $countries, $product->attributes[9]->pivot->value?? '', array('id' => 'attribute_product_country_of_origin', 'class' => 'form-control bm-select' , 'placeholder' => 'Country Of Origin','style' => 'width: 100%')) }}
                                @else
                                {{ Form::select('attribute_country_of_origin', $countries, '', array('id' => 'attribute_product_country_of_origin', 'class' => 'form-control bm-select' , 'placeholder' => 'Country Of Origin','style' => 'width: 100%')) }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                                {{ Form::label('product_', 'HS Code', array('class' => 'form-label')) }}
                                @if(isset($product))
                                {{ Form::text('attribute_HS_code', $product->attributes[10]->pivot->value?? '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                                @else
                                {{ Form::text('attribute_HS_code', '', array('id' => 'product_hs_code', 'class' => 'form-control' )) }}
                                @endif
                            </div>
                        </div>
                        @if(isset($product))
                            @foreach ($product->customAttributes as $attribute)
                            <div class="col-md-6">
                                <div class="form-group text-input {{ isset($mode) ? ''  : 'required'}}">
                                    {{ Form::label('product_'.$attribute->attribute, $attribute->attribute, array('class' => 'form-label')) }}
                                    {{ Form::text('attribute_'.$attribute->attribute, $attribute->pivot->value, array('id' => 'attribute_'.$attribute->attribute, 'class' => 'form-control' )) }}
                                </div>
                            </div>
                            @endforeach
                        @endif
            </div>
            <div class="row  navigation-buttons">
                <div class="col-md-8">
                    <div class="biz-button balnk-bordered btn-cancel">Cancel</div>
                </div>
                <div class="col-md-4 back-next-container">
                    <a class="biz-button balnk-bordered btn-back" href="#get-started" data-toggle="tab"
                        style=" ">Back </a>
                        <a class="biz-button colored-default btn-next " href="#looking-for" data-toggle="tab">Next</a>

                </div>
            </div>
        </div>
        {{-- Upload images Tab--}}
        <div class="tab-pane" id="looking-for">
            <div class="row" >
                <div class=" TabHeaderTitle row">
                    <h2 class="TabTitle">Upload Pictures</h2>
                    <h4 class="TabHint"> You can add different images to your Product so the user can eaisly preview
                        products before he buy it .</h4>
                </div>
                <div class=" row col-md-8 col-md-offset-2" style="width:100%">
                    <div class="item-wrapper one">
                        <div class="item">
                            <form data-validation="true" action="#" method="post" enctype="multipart/form-data">
                                <div class="item-inner">
                                    <div class="item-content">
                                        <div class="image-upload"> <label style="cursor: pointer;margin-top:50px;"
                                                for="file_upload">
                                                <img src="{{asset('images/upload-img.svg')}}" alt=""
                                                    class="uploaded-image">
                                                <div class="h-100">
                                                    <div class="dplay-tbl">
                                                        <div class="dplay-tbl-cell">
                                                            <h6 style="color :#9e9e9e" class="mt-10 mb-70">Browse to
                                                                select file </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input data-required="image" type="file" @if(isset($product))
                                                    value="{{$product->images}}" @endif name="fileup[]" id="file_upload"
                                                    class="image-input" multiple>
                                            </label> </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row" id="productImgs" style="margin:0px 30px 0px 30px">
                        @if(isset($product))
                        @foreach($product->images as $image)
                        <div id="imgcon{{$loop->iteration}}" class="col-xs-6 col-sm-4 col-md-2 product-image"
                            style="height:100px;padding:2">
                            <img style=" width:100%;height:100%;object-fit:contain; background-color:#f5f5f5"
                                class="blah-{{$loop->iteration}}" src="{{asset($image->image)}}" />
                            <div class="delete-image" onclick="delete_image({{$loop->iteration}})">
                                <div class="delete-image-icon">X</div>
                                <div class="delete-image-text">Cancel</div>
                            </div>
                        </div>
                        @endforeach
                        @for ($i = count($product->images); $i < 6; $i++) <div id="imgcon{{$i}}"
                            class="col-xs-6 col-sm-4 col-md-2 product-image" style="height:100px;padding:2">
                            <img style=" width:100%;height:100%;object-fit:contain; background-color:#f5f5f5 ;border:0;"
                                class="blah-{{$i+1}} image" />
                            <div class="delete-image" onclick="delete_image({{$i}})">
                                <div class="delete-image-icon">X</div>
                                <div class="delete-image-text">Cancel</div>
                            </div>
                    </div>
               
                @endfor
                {{Form::hidden('images_count', count($product->images) , array('id'=> 'images_count'))}}

                @elseif(old('fileup'))
                @foreach(old('fileup') as $image)
                <div id="imgcon{{$loop->iteration}}" class="col-xs-6 col-sm-4 col-md-2 product-image"
                    style="height:100px;padding:2">

                    <img style=" width:100%;height:100%;object-fit:contain; background-color:#f5f5f5"
                        src="{{ $image }}" />
                </div>
                @endforeach
                @for ($i = count(old('fileup')); $i < 6; $i++) <div id="imgcon{{$i}}"
                    class="col-xs-6 col-sm-4 col-md-2 product-image" style="height:100px;padding:2">
                    <img style=" width:100%;height:100%;object-fit:contain; background-color:#f5f5f5 ;border:0;"
                        class="blah-{{$i}}" />
                    <div class="delete-image" onclick="delete_image({{$i}})">
                        <div class="delete-image-icon">X</div>
                        <div class="delete-image-text">Cancel</div>
                    </div>
            </div>
            @endfor
            @else
            @for ($i = 0; $i < 6; $i++) <div id="imgcon{{$i}}" class="col-xs-6 col-sm-4 col-md-2"
                style="height:100px;padding:2">
                <img style=" width:100%;height:100%;object-fit:contain; background-color:#f5f5f5 ;border:0;"
                    class="blah-{{$i}}" />
                <div class="delete-image" onclick="delete_image({{$i}})">
                    <div class="delete-image-icon">X</div>
                    <div class="delete-image-text">Cancel</div>
                </div>
        </div>
        @endfor
        {{Form::hidden('images_count', 0 , array('id'=> 'images_count'))}}

        @endif
        <progress id="fprog{{$i}}" class="hidden" max="100"></progress>

        @if ($errors->has('images_count')) <p class="bg-danger">{{ $errors->first('images_count') }}</p> @endif
    </div>
    </div>
    <div class="row  navigation-buttons">
        <div class="col-md-8" style="margin-top: 100px;">
            <div class="biz-button balnk-bordered btn-cancel">Cancel</div>
        </div>
        <div class="col-md-4 back-next-container"  style="margin-top:100px">
            <a class="biz-button balnk-bordered btn-back" href="#about-you" data-toggle="tab">Back </a>
            @if(!isset($product))
            {{ Form::submit('Create New Product', array( 'class' =>'biz-button colored-default  btn-create ')) }}
            @else
            {{ Form::submit('Update Product', array( 'class' =>'biz-button colored-default btn-create ')) }}
            @endif
        </div>
    </div>

</div>
{{-- <div class="btn btn-info bm-btn mt-3" id="addProductSubmitButton" >Submit</div> --}}
{{ Form::close() }}
</div>
</div>
</div>
</div>
@push('scripts')
<script>
    function show_price_offer() {
        $('.priceOffer').toggleClass("hidden");
    }

    function addCustField(name = '') {
        if (name.indexOf(' ') !== -1) {
            _name = name.replace(/\s/g, '_');
        } else {
            _name = name;
        }
        var attHTML =
            `
                <div class="form-group col-md-6" id="` + _name + `" >          
                      <div class="form-group col-md-12 text-input" style="display:flex ; padding: 0">
                        <label class="form-label">`+name+`</label>
                        <input type="text" name=attribute_` + _name + ` class="form-control"/>
                        <span class="glyphicon glyphicon-trash" onclick="delCustField(` + name + `)" style="cursor:pointer;align-self:center;padding :2px"></span>
                      </div> 
                    
                </div>
                ` +
            '</div></div></div>';
        $("#product-details-section").append(attHTML);
        $('.popover').hide();
    }

    function delCustField(id) {
        $(id).remove();
    }

    function delete_image(iteration) {
        $('.blah-' + iteration).attr('src', null);
    }
    $(document).ready(function () {

        var selectedCategory = $('#product_category').val();
        $.ajax({
            url: window.location.protocol + '//' + window.location.host +
                '/companies/product/get_subCategory/' +
                selectedCategory,
            method: "GET",
            success: function (response) {
                $('#product_subCategory').empty();
                $.each(response, function (index, category) {
                    $('#product_subCategory').append(
                        $('<option></option>').val(category.id).html(
                            category.category));

                });
            },
        });


        $('#product_category').on('change', function () {
            var selectedCategory = $(this).val();
            $.ajax({
                url: window.location.protocol + '//' + window.location.host +
                    '/companies/product/get_subCategory/' +
                    selectedCategory,
                method: "GET",
                success: function (response) {
                    $('#product_subCategory').empty();
                    $.each(response, function (index, category) {
                        $('#product_subCategory').append(
                            $('<option></option>').val(category.id).html(
                                category.category));
                    });
                },
            });
        });

        @if(isset($product))
          $('.text-input').addClass('focused');
          $('.select2-selection.select2-selection--single').addClass('focused');
        @endif
    
    });
</script>
@endpush
@endsection
