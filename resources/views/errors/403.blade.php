@extends('layouts.app') 
@section('content')
	<div class="row">	<!-- row 1 -->
		<div class="col-md-12">
            <div class="error-template text-center">
                <h1>
                    Error</h1>
                <h3>
					@if ($exception->getMessage() == '')
						Error 403. This action in unauthorized
					@else
						{{ $exception->getMessage() }}
					@endif					
					<br>&nbsp;<br>
				</h3>
                <div class="error-details">
                    
                </div>
                <div class="error-actions">
                    <!--<a href="/home" class="btn btn-warning btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a>-->
                </div>
            </div>
        </div>
	</div>				<!-- end row 1 -->
@stop
 

