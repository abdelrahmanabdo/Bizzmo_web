@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	<div class="container">		
		{{ Form::open(array('id' => 'frmManage')) }}
			<ul class="nav nav-tabs">
			<li class="active"><a href="#home">Default</a></li>
			<li><a href="#menu1">Menu 1</a></li>
			<li><a href="#menu2">Menu 2</a></li>
			<li><a href="#menu3">Menu 3</a></li>
		</ul>
		  <div class="tab-content">
			<div id="home" class="tab-pane fade in active">
			  <h3>Default</h3>
			  <label>username</label><br/>
			  <input name="username" type="text" >
			</div>
			<div id="menu1" class="tab-pane fade">
			  <h3>Menu 1</h3>
			   <label>name</label><br/>
			 <input name="name" type="text" >
			</div>
			<div id="menu2" class="tab-pane fade">
			  <h3>Menu 2</h3>
			   <label>password</label><br/>
			  <input name="password" type="password" >
			</div>
			<div id="menu3" class="tab-pane fade">
			  <h3>Menu 3</h3>
			   <label>email</label><br/>
			  <input name="email" type="email" ><br/>
			  <input name="submit" type="submit" >
			</div>
		  </div>
		</form>
	</div>
@stop	
@push('scripts')	
	<script>
	$(document).ready(function(){
		$(".nav-tabs a").click(function(){
			$(this).tab('show');
		});
		$('.nav-tabs a').on('shown.bs.tab', function(event){
			var x = $(event.target).text();         
			var y = $(event.relatedTarget).text();  
			$(".act span").text(x);
			$(".prev span").text(y);
		});
	});
	</script>
@endpush
