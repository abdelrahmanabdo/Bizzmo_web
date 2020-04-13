@extends('layouts.app') 
@section('content')
	<div class="row row-fluid">	<!-- row 1 -->
		<div class="col-md-12">
            <div class="text-center col-md-offset-3 col-md-6">
				@if (Auth::guest())
					<br><br>
				@endif
                <!-- <h3 class="bm-error-header">Sorry!</h3> -->
                <h3 class="bm-msg-header {{ (!empty($error) ? 'error' : 'info') }}">{{ $message }}</h3>
                @if (isset($description))
                <span class="bm-circle"></span>
                <p class="bm-msg-details {{ (!empty($error) ? 'error' : 'info') }}">{{ $description }}</p>
                @endif 
				@if (isset($company_id))
                    <a class="bm-btn bm-top-space" href="{{ url('/companies/' . $company_id) }}" >Click here to complete</a>
                    <br>&nbsp;<br>
                @endif
                @if (isset($home_link))
                    <a class="bm-btn bm-top-space" href="{{ url('/home')}}">Home</a>
                    <br>&nbsp;<br>
				@endif
                <div class="error-details">
                    
                </div>
                <div class="error-actions">
                    <!-- <a href="/home" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>Home</a>						 -->
                </div>
            </div>
        </div>
	</div>				<!-- end row 1 -->
@stop
 

