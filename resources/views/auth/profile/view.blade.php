@extends('layouts.app' ,['hideRightMenuAndExtend' => true])
@section('content')
<div class="row profile">
	<div class="col-md-12 biz-mt-2">
		<h3 class="bm-title" style="margin-bottom: 10px">Account Information</h3>
	</div>	
	<div class="white-box col-sm-12">

	<div class="col-md-12 biz-mt-3">
		<div class="col-md-6">
			<div class="control-label bm-label col-sm-2">Name</div>
			<p><?= $user->name ?></p>
		</div>
		<div class="col-md-6">
			<div class="control-label bm-label col-sm-2">Email</div>
			<p><?= $user->email ?></p>
		</div>
	</div>
	<div class="col-md-12 biz-mt-3">
		<div class="col-md-6">
			<div class="control-label bm-label col-sm-2">Job title</div>
			<p><?= $user->title ?></p>
		</div>
		<div class="col-md-6">
			<div class="control-label bm-label col-sm-2">Mobile</div>
			<p><?= $user->phone() ? $user->phone()->phone : "Not registered yet" ?></p>
		</div>
	</div>		
	<div class="col-md-12 biz-mt-3">
		<div class="col-md-6">
			<div class="control-label bm-label ">Roles</div>
			<ul class="list-group">
				@foreach ($user->roles as $role)
				<li class="list-group-item" style="border:none;padding:0"><p><?= $role->rolename ?></p></li>
				@endforeach
			</ul>
		</div>
	</div>
	
	<div class="col-md-12" style="margin: 25px 0px">
		<a href="/profile/edit" class="biz-button colored-yellow" style="margin : 0px" role="button">
			{{-- <span class="edit-icon-white hidden-xs" title="Edit"></span> --}}
			<span class="">Edit</span>
		</a>
	</div>
	</div>
</div>
@stop
