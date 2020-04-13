@extends('layouts.master') 
@section('content')
	<div class="row">	<!-- row 1 -->
		<div class="col-md-12">
            <div class="error-template text-center">
                <h1>Oops!</h1>
                <h3>
					@if ($exception->getMessage() == '')
						406 Not accepted
					@else
						{{ $exception->getMessage() }}
					@endif
					<br>&nbsp;<br>
				</h3>
                <div class="error-details">
                    
                </div>
                <div class="error-actions">
                    <a href="/home" class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-home"></span>
                        Take Me Home </a>						
                </div>
            </div>
        </div>
	</div>				<!-- end row 1 -->
@stop
 

