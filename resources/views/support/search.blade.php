@extends('layouts.app') 
@section('content')
	@if (empty($status))
	{{ Form::open(array('id' => 'frmManage')) }}
		<div class="">	<!-- row 1 -->		
			<div class="col-sm-6">  <!-- column 1 -->
				<div class="form-group"> <!-- User name -->  
					{{ Form::label('name', 'User name') }}
					{{ Form::text('name', Input::get('name'), array('id' => 'name', 'class' => 'form-control')) }}			
					@if ($errors->has('name')) <p class="bg-danger">{{ $errors->first('name') }}</p> @endif
				</div> <!-- User name -->  
			</div>					<!-- end col 1 -->
			<div class="col-sm-6">  <!-- column 2 -->
				<div class="form-group"> <!-- Company name -->  
					{{ Form::label('company', 'Company name') }}
					{{ Form::text('company', Input::get('company'), array('id' => 'company', 'class' => 'form-control')) }}			
					@if ($errors->has('company')) <p class="bg-danger">{{ $errors->first('company') }}</p> @endif
				</div> <!-- Company name -->  
			</div>					<!-- end col 2 -->				
		</div>				<!-- end row 1 -->
		<div class="">	<!-- row 2 --> 
			<div class="col-sm-6">  <!-- column 1 -->
				<div class="form-group"> <!-- Message -->  
					{{ Form::label('message', 'Message') }}
					{{ Form::text('message', Input::get('message'), array('id' => 'message', 'class' => 'form-control')) }}			
					@if ($errors->has('message')) <p class="bg-danger">{{ $errors->first('message') }}</p> @endif
				</div> <!-- Message -->  
			</div>					<!-- end col 1 -->
			<div class="col-md-6">  <!-- column 3 -->
				<div class="form-group"> <!-- status -->  
					{{ Form::label('status_id', 'Status') }}
					{{ Form::select('status_id', $statuses, Input::get('status_id'),array('id' => 'status_id', 'class' => 'form-control bm-select'))}}		
					@if ($errors->has('status_id')) <p class="bg-danger">{{ $errors->first('status_id') }}</p> @endif
				</div> <!-- company end -->  
			</div>	
		</div>				<!-- end row 2 -->
		<div class="">	<!-- row 3 --> 
			<div class="col-sm-12"> <!-- column 1 -->
				@if (isset($mode))
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					<a href="" class="btn bm-btn fixedw_button" id="lnksubmit">
						<span class="glyphicon glyphicon-search" title="Search"></span>
					</a>
				@endif    		
			</div> <!-- column 1 end -->
		</div> <!--row 3 end -->
	{{ Form::close() }}
	@endif
	@if (isset($supports))
		<div class="">	<!-- row 4 -->
			<div class="col-sm-12"> <!-- column 1 -->
				<table id="mytable" class="table table-striped table-bordered table-hover table-condensed">
				<thead>
					<tr>
						<th>Name</th>
						<th>Title</th>
						<th>Email</th>
						<th style="max-width: 300px;">Message</th>
						<th>Status</th>
						<th class="no-sort" width="10%">
							@if (Gate::allows('us_cr'))
							<a href="{{ url("/supports/create") }}" role="button"><span class="glyphicon glyphicon-plus" title="Add"></span></a>	
							@endif
						</th>
					</tr>		
				</thead>
				<tbody>			
					  @foreach ($supports as $support)
						<tr>
							<td> {{ $support->issuer_name }} </td>
							<td> {{ $support->title }} </td>
							<td> {{ $support->issuer_email }} </td>							
							<td class="text-truncate"> {{ $support->message }} </td>
							<td><span class="<?= $support->isOpen() ? 'red' : 'green'?>"> {{ $support->status_name }} </span></td>
							<td>
								@if (Gate::allows('su_vw'))
									<a href="{{ url("/supports/view/" . $support->id) }}" role="button" class="view-icon" title="View"></a>
								@endif
							</td>
						</tr>	
					  @endforeach			
				</tbody>
				</table>
			</div>
		</div>				<!-- end row 4 -->
	@endif
@stop
@push('scripts')
	<script type="text/javascript">
		$(document).ready(function(){		
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
		   	$("#submit").click();
			});				
	});
	</script>
@endpush 

