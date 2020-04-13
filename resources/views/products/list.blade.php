@extends('layouts.app')
@section('title')
@if (isset($title))
{{ $title }}
@endif
@stop
@section('content')
@include('includes.company-profile-head')
<div class="categories col-md-12 col-xs-12">
	<div class="categoryHeading">
		<div class="categoryTitle">Product Categories</div>
		<div class="categoryButtons">
			<a class="addProduct btn " href="{{route('addProduct')}}"> <img src="{{asset('images/add-background.svg')}}" />Add
				New Product</a>
		</div>
	</div>
	<div class="categories-container">
		@forelse ( $categories as $category)
		<div class="categoryBox">
			<div class="categoryImage">
				<img src="{{$category->image}}" />
			</div>
			<div class="categoryDetails">
				<div class="categoryName"> <a
						href="{{route('showCategoryProducts',$category->id)}}">{{$category->category}} </a></div>
				<div class="categoryDescription">{{$category->description}}</div>
			</div>
		</div>
		@empty
			<div class="text-danger text-center  col-md-offset-4 col-md-4 ">No Categories Yet</div>
		@endforelse
	</div>


</div>
@endsection

<style>
	
	.categories-container {
		display: flex;
		flex-direction: row;
		width : 100%;
		margin-bottom : 15px;
	}
	.categoryBox {
		width: 221px;
		background-color: white;
		height: fit-content;
		margin-right: 15px;
	}

	/**Category */
	.categories .categoryHeading {
		display: flex;
		justify-content: space-between;

	}

	.categoryTitle {
		width: 75%;
		font-family: Montserrat;
		font-size: 18px;
		font-weight: 600;
		color: #1a425e;
	}

	.categoryButtons {
		display: flex;
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
	.categoryImage{
		width: 100%;
  height: 180px;
  border-radius: 4px;
	}
	.categoryImage img {
		height: 100%;
		width: 100%;

	}

	.categoryDetails {
		width: 100%;
		height: fit-content;
		padding-top: 4px;
		padding: 16px 8px;
	}
	.categoryName {
		text-align: center;

	}
	.categoryName a {
		font-family: Montserrat;
		font-size: 14px;
		font-weight: 500;
		font-stretch: normal;
		font-style: normal;
		line-height: 1.43;
		letter-spacing: normal;
		text-align: center;
		color: #1a425e;		

	}

	.categoryName:hover a{
		text-decoration: none;
		cursor: pointer;
	}

	.categoryDescription {
		padding-left: 20px;
		color: #bdbdbd;
		max-height: 40px;
		overflow: hidden;
	}
</style>