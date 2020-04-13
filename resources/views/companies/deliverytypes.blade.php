@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{{ Form::open(array('id' => 'frmManage', 'class' => 'payment-term-form')) }}	
		<div class="row-fluid bm-pg-header">
			<h2 class="bm-pg-title">{{ $title}}</h2>
		</div>
		<div class="row-fluid">
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					{!! Form::label('companyname', 'Company name' ,['class' => 'label-view', 'style' => 'margin-bottom: 0']) !!}
					<p class="form-control-static" style="padding-bottom: 0">{{ $company->companyname }}</p>
					{{ Form::hidden('company_id', $company->id, array('id' => 'id')) }}
					@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
				</div>
			</div>
			@if (!isset($mode))
				<div class="col-md-6 col-xs-12">
					<div class="form-group">
						{{ Form::label('deliverytype_id', 'Delivery Types', ['class' => 'label-view']) }}
						{{ Form::select('deliverytype_id', $deliverytypes, Input::get('deliverytype_id'),array('id' => 'deliverytype_id', 'class' => 'form-control bm-select select-with-icon'))}}		
						<a href="" id="lnkdeliverytype" role="button" class="add-icon" title="Add delivery type"></a>					
						@if ($errors->has('deliverytype_id')) <p class="bg-danger">{{ $errors->first('deliverytype_id') }}</p> @endif
					</div>
				</div>
			@endif
		</div>
		<div class="row-fluid">
			<div class="col-sm-12">
				<table id="listtable" class="table table-striped table-bordered table-hover">
					<thead>
						<tr>
							<th>Delivery Type</th>
							@if (!isset($mode))
								<th class="no-sort" width="10%">
									&nbsp;
								</th>
							@endif
						</tr>		
					</thead>
					<tbody>			
							@if (old('id'))
								@php
									$i = 0;
								@endphp
								@foreach (old('id') as $item)
									<tr>
										<td>
											<input name="id[]" type="hidden" value="{{ old('id')[$i] }}">
											<input name="deliverytypedel[]" id="deliverytypedel" type="hidden" value="{{ old('deliverytypedel')[$i] }}">										
											@if (old('id')[$i] != 1)
												<a href="#" class="delete-icon" onclick="deldt(this);return false;" id="btnDeldt" type="button" title="Delete delivery type"></span></a>
											@endif
										</td>
										<td>
											{{ Form::hidden('dt_id[]', old('dt_id')[$i], array('id' => 'dt_id', 'class' => 'form-control')) }}
											{{ Form::hidden('dt_name[]', old('dt_name')[$i], array('id' => 'dt_name', 'class' => 'form-control')) }}
											{{ old('dt_name')[$i] }}
										</td>
									</tr>
									@php
										$i++;
									@endphp	
								@endforeach
							@else	
								@foreach ($company->deliverytypes as $deliverytype)
									<tr>
										<td>
											{{ Form::hidden('dt_id[]', $deliverytype->id, array('id' => 'dt_id', 'class' => 'form-control')) }}
											{{ Form::hidden('dt_name[]', $deliverytype->name, array('id' => 'dt_name', 'class' => 'form-control')) }}
											{{ $deliverytype->name }}
										</td>
										@if (!isset($mode))
											<td>
												<input name="id[]" type="hidden" value="{{ $deliverytype->id }}">
												<input name="deliverytypedel[]" id="deliverytypedel" type="hidden" value="">
												@if ($deliverytype->id != 1)
													<a href="#" class="delete-icon" onclick="deldt(this);return false;" id="btnDeldt" type="button" title="Delete delivery type"></span></a>
												@endif
											</td>
										@endif					
									</tr>	
								@endforeach			
							@endif
					</tbody>
				</table>
			</div>
		</div>
		<div class="row-fluid">	<!-- row 3 --> 
			<div class="col-md-12 col-lg-12"> <!-- Column 1 -->
				@if (isset($mode))
				@else
					{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
					<a href="" class="btn bm-btn green fixedw_button" id="lnksubmit" title="Save">
						<span class="glyphicon glyphicon-ok hidden-xs hidden-sm"></span>
						<span class="visible-xs visible-sm">Save</span>
					</a>
				@endif
			</div> <!-- Column 1 end -->
		</div> <!--row 3 end -->		
	{{ Form::close() }}
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});				
			//lnkdeliverytype
			$("#lnkdeliverytype").bind('click', function(e) {
				e.preventDefault();
				//alert('aa');
				var table = document.getElementById('listtable');
				var rowLength = table.rows.length;
				if (rowLength > 1) {
					for (var i = 1; i <= rowLength - 1; i += 1){
						var row = table.rows[i];
						deliverytype = row.cells[0].innerHTML;
						if (deliverytype.includes($('#deliverytype_id option:selected').text()))  {
							if (row.style.display != 'none') {
								alert("Already assigned");
								return false;
							}										
						}
					}
				}	
				var row = '<tr>';							
				row = row + '<td>';
				row = row + $('#deliverytype_id option:selected').text();
				row = row + '<input name="dt_id[]" type="hidden" value="' + $('#deliverytype_id option:selected').val() + '">';
				row = row + '<input name="dt_name[]" type="hidden" value="' + $('#deliverytype_id option:selected').text() + '">';
				row = row + '</td>';
				row = row + '<td>';							 
				row = row + '<input name="id[]" type="hidden" class="form-control">';
				row = row + '<input name="deliverytypedel[]" id="deliverytypedel" type="hidden" class="form-control">';
				row = row + '<a href="#" class="delete-icon" onclick="deldt(this);return false;" id="btnDel" type="button" title="Delete delivery type"></a>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#listtable').append(row);
				$("#deliverytypecount").val(parseInt($("#deliverytypecount").val()) + 1);
			});
			//lnkdeliverytype end
		});
		function deldt(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
				var inputval = inputs[j].id;                
				inputs[j].value  = 1;
			}
			tr.style.display = 'none';
		}
	</script>
@endpush 