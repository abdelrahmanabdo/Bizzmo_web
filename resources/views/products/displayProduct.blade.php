@extends('layouts.app')
@section('title')
@if (isset($title))
{{ $title }}
@endif
@stop
@section('content')
<div class="page-layout">
    <div class="page-head col-md-12 col-sm-12 row">
        <div class="head-text col-md-10">
            <span class="breadcrumb">
                Product Categories > Electronics
            </span>
            <h4 class="product-name">
                {{$product->name}}
            </h4>
        </div>
        <div class="head-edit-button col-md-2">
            <div class="edit-button">
                <a class="editProductButton btn " href="{{route('showEditProduct',$product->id)}}">
                    <img src="{{asset('images/edit-icon.svg')}}" /> Edit Product</a>
            </div>
        </div>
    </div>
    <div class="product-basic-information col-md-12 col-sm-12 row ">
        <div class="images-slider col-md-12">
        @if($product->images->count() > 0)
            <div class="current-image col-md-12 ">
                <img src="{{asset($product->images[0]->image)}}" />
            </div>
            <div class="other-images col-md-12">
                @foreach ($product->images as $image)
                <div class="image">
                    <img src="{{asset($image->image)}}" />
                </div>
                @endforeach
            </div>
        @endif
        </div>
        <div class="product-info col-md-12">
            <div class="nameContainer">
                {{$product->name}}
            </div>
            <div class="descriptionContainer">
                <div class="title">
                    Description :
                </div>
                <div class="description">
                    <p>{{$product->description}} </p>
                    <p class="read-more"><a href="#Description" class="button">See More</a></p>
                </div>

            </div>
            <div class="priceContainer col-xs-12">
                <div class="offer-price">{{$product->offer ? $product->offer.' '.$product->getCurrency(): ''}}</div>
                <div class="original-price">{{$product->price . ' '.$product->getCurrency()}}</div>
            </div>
            <div class="addToCartContainer col-xs-12">
                <div class="productQuantity col-xs-12">
                    <a href="#" class="plus"><img src="{{asset('images/arrow-up.svg')}}" /></a>
                    <div class="qty">1</div>
                    <a href="#" class="minus"><img src="{{asset('images/arrow-down.svg')}}" /></a>
                </div>
                <div id="addToIquiry" class="addToCart col-md-5 col-xs-12">
                    <img class="addToCartIcon" src="{{asset('images/cart.svg')}}">
                    <h4> Add To Cart </h4>
                </div>
            </div>
        </div>
    </div>
    <div class="product-specifications  col-md-12 col-sm-12 row ">
        <div class="tab-header">
            <a class="active Description" onclick="toggleTab('Description')">Description</a>
            <a class="Specifications" onclick="toggleTab('Specifications')">Specifications</a>
        </div>
        <div class="specifications-content">
            <div id="Description" class="tabs">
                <p> {{$product->description}}</p>
            </div>
            <div id="Specifications" class="tabs" style="display:none">
                @forelse($product->attributes as $attribute)
                    @if($attribute->pivot->value != null)
                    <div class="specification col-md-6">
                        <div class="specification-name col-md-6">{{str_replace('_' , ' ',$attribute->attribute)}}</div>
                        <div class="specification-value col-md-6">{{$attribute->pivot->value}}</div>
                    </div>
                    @endif
                @empty
                <div class="no-specifications">
                    <h4> No Specifications</h4>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @if(isset($similarProducts) && count($similarProducts) > 0)
    <div class="similar-products  col-md-12 col-sm-12 row ">
        <div class="title">
            Similar Products
        </div>
        <div class="products owl-carousel col-md-12 ">
            @foreach ($similarProducts as $similar )
            <div class="productBox item ">
                <div class="productImage">
                    <img src="{{count($similar->images) > 0 ? asset($similar->images[0]->image ): ''}}" />
                </div>
                <div class="productDetails">
                    <div class="productName">
                        <a href="{{route('displayProductDetails',$similar->id)}}">{{$similar->name}}
                        </a>
                    </div>
                    <div class="productPriceContainer">
                        <div class="productPrice" @if($similar->offer != Null) style="text-decoration : line-through;
                            color:
                            #bdbdbd; font-size:14px; font-weight:400 "
                            @endif>
                            {{$similar->price . ' '. $similar->getCurrency()}}
                        </div>
                        @if($similar->offer != Null)
                        <div class="productPriceOffer">
                             {{$similar->offer . ' '. $similar->getCurrency()}}
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('addToInquiry', ['product_id' => $similar->id , 'supplier_id'=> $similar->company_id])}}" class="addToCart">
                        <img class="addToCartIcon" src="{{asset('images/cart.svg')}}">
                        <h4> Add To Cart </h4>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function () {
        @if(Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
        @endif
        $('.owl-carousel').owlCarousel({
            margin: 1,
            padding: 2,
            navigation: false,
        });
        $(".productQuantity .plus").on('click' , function (e){
            e.preventDefault();
            $(".productQuantity .qty").text(parseInt($(".productQuantity .qty").text())+1);
        });
        $(".productQuantity .minus").on('click' , function (e){
            e.preventDefault();
            if($(".productQuantity .qty").text() != 1){
                $(".productQuantity .qty").text(parseInt($(".productQuantity .qty").text())-1);
            }
        });
        $('.product-basic-information .images-slider .other-images img').on('click', function () {
            var imageURL = $(this).attr('src');
            $('.product-basic-information .images-slider .current-image img').attr('src', imageURL);
        });

        $('#addToIquiry').on('click',function(){
           url = `{{ route('addToInquiry')}}`;
            $.ajax({
					url: url,
					type:'GET',
					data: {
                        'product_id' : {{$product->id}},
                        'supplier_id': {{$product->company_id}},
                        'qty' : $(".productQuantity .qty").text(),
                        'withQty' : true
					},
					success: function(data){
                        if(data == 'success'){
                           toastr.success("The product was added successfully");   
                        }else{
                            toastr.error("The product has already been added before");
                        }
                    }, 
					error: function(output_string){}
				}); 
            
        });
    });

    function toggleTab(tab) {
        var i;
        var x = document.getElementsByClassName("tabs");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
            x[i].classList.remove('active');
        }
        document.getElementById(tab).style.display = "block";
    }
</script>
@endpush
@endsection
