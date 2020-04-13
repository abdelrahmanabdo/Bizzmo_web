@extends('layouts.app')
@section('title')
@if (isset($title))
{{ $title }}
@endif
@stop
@section('content')
@include('includes.company-profile-head')

<div class="">
	<div class="categories col-md-12 col-xs-12">
		<div class="categories-head col-md-12">
			<div class="categoryName">{{$category->category}} ({{count($products)}} Items)</div>
			<div class="categoryButtons">
				<div class="filter btn "><img src="{{asset('/images/filter.svg')}}" />Filter</div>
				<a class="addProduct btn" href="{{route('addProduct')}}"> <img src="{{asset('images/add-background.svg')}}" />Add
					New Product</a>
			</div>
		</div>
		<div class="owl-carousel col-md-12">
			<div class="item">
				<a href="#" id="all-{{$category->id}}">All</a>
			</div>
			@forelse ( $subCategories as $category)
			<div class="item">
				<a href="#" id="categoty-{{$category->id}}">{{$category->category}}</a>
			</div>
			@empty

			@endforelse
		</div>
	</div>
</div>
<div class="products col-md-12">
	@forelse ( $products as $product)
	<div class="productBox  ">
		<div class="productImage">
			 <img src="{{count($product->images) > 0 ?asset($product->images[0]->image ): ''}}"  /> 
		</div>
		<div class="productDetails">
			<div class="productName">
				<a href="{{route('displayProductDetails',$product->id)}}">{{$product->name}}
				</a>
			</div>
			<div class="productPriceContainer">
				<div class="productPrice" @if($product->offer != Null) style="text-decoration : line-through; color:
					#bdbdbd; font-size:14px; font-weight:400 " @endif>
					$ {{$product->price}}
				</div>
				@if($product->offer != Null)
				<div class="productPriceOffer">
					$ {{$product->offer}}
				</div>
				@endif
			</div>
		<a href="{{route('addToInquiry' , ['product_id' => $product->id , 'supplier_id'=>$product->company_id])}}" class="  biz-button colored-default addToCart">
				<img class="addToCartIcon" src="{{asset('images/cart.svg')}}">
				<h4> Add To Cart </h4>
			</a>
		</div>
	</div>
	@empty
		<div class="text-danger text-center  col-md-offset-4 col-md-4">No Products</div>
	@endforelse
</div>
@endsection
@push('scripts')
<script type="text/javascript">
	$(document).ready(function () {
		@if(Session::has('success'))
                toastr.success("{{ Session::get('success') }}");
        @endif
        @if(Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
        @endif
		$('.owl-carousel .item').on('click', function () {
			var $id = $(this).children("a").attr('id');
			$('.owl-item').removeClass('activeCategory');
			$('.owl-item:first-child').css('background-color' , 'rgb(187, 187, 187)');

			$(this).parent().toggleClass('activeCategory').fadeIn();
			$.ajax({
				url: 'http://' + window.location.host + '/companies/product/get_subCategory/' +
					$id + '/products',
				dataType: "json",
				method: 'GET',
				success: function (response) {
					$('.products').empty();
					$.each(response, function (index, product) {
						$('.products').append(`
						<div class="productBox">
							<div class="productImage">
									<img src="`+window.location.protocol+'//'+window.location.host+'/'+product.images[0].image +`" />
							</div>
							<div class="productDetails">
								<div class=" productName"> 
									<a href="`+window.location.protocol+'//'+window.location.host+`/companies/product/`+ product.id + `">` + product.name + `
									</a>
								</div>
								<div class="productPriceContainer">
										<div class="productPrice" ` + (product.offer != null ? ` style="text-decoration : line-through; color: #bdbdbd; font-size:14px; font-weight:400 " ` :
								``) + ` >
											$` + product.price + `
										</div>
										` + (product.offer != null ? `<div class="productPriceOffer"> $` + product.offer + `</div>` : ``) +
							`	
								</div>
									<a href="{{route('addToInquiry' , ['product_id' => $product->id , 'supplier_id'=>$product->company_id])}}" class="biz-button colored-default  addToCart">
										<img class="addToCartIcon" src="{{asset('images/cart.svg')}}">
										<h4>Add To Cart</h4>
									</a>
								</div>
							</div>
						</div>
						`);

					});
				},

			});
		});
		$('.owl-carousel').owlCarousel({
        margin: 1,
        padding: 2,
    });

	});
</script>
@endpush
<style>
	/**category*/
	.owl-carousel {
		padding: 0px 15px 15px 14px;
	}

	.owl-carousel .owl-item {
		width: 0 !important;
		width: fit-content !important;
		padding: 6px;
		border: 1px solid #9e9e9e;
		border-radius: 4px;
		cursor: pointer;
		margin: 4px;
		text-align: center;
	}

	.owl-carousel .owl-item a {
		color: #757575;
		text-decoration: none;
	}

	.owl-carousel .owl-item:first-child {
		width: 70px !important;
		background-color: #3ba2ab;
		color: #fff;
		border: 0;
		border-radius: 4px;

	}

	.owl-carousel .owl-item:first-child a {
		color: #fff;
	}

	.activeCategory {
		background-color: #3ba2ab !important;
		color: #fff;
		border: 0px;
		border-radius: 4px;
	}

	.activeCategory .item a{
		color: #fff;

	}
	.categories-head{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		padding: 0 !important;
	}
	/**Category */
	.categories  {
		padding: 0 !important;

	}

	.categoryName {
		font-family: Montserrat;
		font-size: 18px;
		font-weight: 600;
		font-stretch: normal;
		font-style: normal;
		line-height: 1.33;
		letter-spacing: normal;
		color: #1a425e;
	}
	.categoryName:hover a{
		text-decoration: none;
	}

	.categoryButtons .filter {
		border-radius: 4px;
		height: 40px;
		border: solid 1px #41b4be;
		font-family: Montserrat;
		font-size: 14px;
		font-weight: 500;
		color: #41b4be;
	}

	.categoryButtons .addProduct {
		border-radius: 4px;
		height: 40px;
		background-color: #41b4be;
		font-family: Montserrat;
		font-size: 14px;
		margin-left: 10px;
		font-weight: 600;
		line-height: 1.57;
		letter-spacing: 0.17px;
		color: #ffffff;
	}

	.categoryButtons .addProduct img {
		width: 20px;
		height: 20px;
		margin: 4px;
	}

	.categoryButtons .addProduct:hover  {
		color :#fff;
	}
	.categoryButtons .filter:hover{
		color :#41b4be;
	}
	.categoryButtons .filter img {
		width: 20px;
		height: 20px;
		margin: 4px;
	}

	/**Products*/
	.products {
		display: flex;
		flex-direction: row;
		margin-top: 16px;
		flex-wrap: wrap;
		padding: 0 !important;
	}
	.productBox {
		width: 221px;
		margin:5px 5px 5px 0px;
		background-color: white;
		height: fit-content;
		border-radius: 4px;
 		box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.1);
 		background-color: #ffffff;
	}

	.productImage img {
		width: 100%;
		height: 155px;
	}

	.productDetails {}

	.productName {
		padding: 2px;
		margin: 5px;
		height: 40px;
		
	}

	.productName a {
  font-family: Montserrat;
  font-size: 14px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.43;
  letter-spacing: normal;
  color: #424242;
	}

	.productPriceContainer {
		height: fit-content;
		position: relative;
		margin : 8px 0px;
	}

	.productPrice {
		font-family: Montserrat;
		font-size: 16px;
		font-weight: 600;
		font-stretch: normal;
		font-style: normal;
		line-height: 1.38;
		letter-spacing: 0.19px;
		color: #1a425e;
		bottom: 0;
		position: absolute;
		bottom: 5px;
	}

	.productPriceOffer {
		margin-left: 10px;
		color: #1a425e;
		height: 20px;
		font-family: Montserrat;
		font-weight: 600;
	}

	.productDescription {
		padding-left: 20px;
		color: #bdbdbd;
		max-height: 40px;
		overflow: hidden;
	}

	.addToCart{
		padding: 0px !important;
		margin : 8px !important;
	}

	.addToCart h4 {
		font-family: Montserrat;
		color: #fff;
		font-size: 14px;
	}

	.addToCartIcon {
		width: 20px;
		height: 20px;
		margin: 5px;
	}
</style>