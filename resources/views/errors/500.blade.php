@extends('layouts.app') 
@section('content')
	<div class="row row-fluid">	<!-- row 1 -->
	<div class="col-md-12">
		<div class="text-center col-md-offset-3 col-md-6">
			<!-- <h3 class="bm-error-header">Sorry!</h3> -->
			<h3 class="bm-msg-header error">System error 500</h3>
							<span class="bm-circle"></span>
			<p class="bm-msg-details error">An error has been logged. We will fix it ASAP</p>
			 
												<a class="bm-btn bm-top-space" href="/home">Home</a>
				<br>&nbsp;<br>
							<div class="error-details">
				
			</div>
			<div class="error-actions">
				<!-- <a href="/home" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>Home</a>						 -->
			</div>
		</div>
	</div>
</div>				<!-- end row 1 -->
@stop
 

