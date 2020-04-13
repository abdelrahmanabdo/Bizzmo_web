@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{{ Form::open(array('id' => 'frmManage', 'class' => 'payment-term-form')) }}	
		<div class="row-fluid bm-pg-header">
			<h2 class="bm-title">{{ $title}}</h2>
		</div>
		<div class="row-fluid">
			<div class="col-md-6 col-xs-12">
				<div class="form-group">
					{{ Form::label('companyname', 'Company name' ,['class' => 'label-view', 'style' => 'margin-bottom: 0']) }}
					<p class="form-control-static" style="padding-bottom: 0">{{ $company->companyname }}</p>
					{{ Form::hidden('company_id', $company->id, array('id' => 'id')) }}
					@if ($errors->has('companyname')) <p class="bg-danger">{{ $errors->first('companyname') }}</p> @endif
				</div>
			</div>
			@if (isset($mode))
				{{ Form::label('supplierterm_id', 'Supplier payment terms', ['class' => 'label-view']) }}
				<p class="form-control-static" style="padding-bottom: 0">{{ $company->vendorpaymentterm->name }}</p>
			@else
				<div class="col-md-6 col-xs-12">
					<div class="form-group">
						{{ Form::label('supplierterm_id', 'Supplier payment terms', ['class' => 'label-view']) }}
						{{ Form::select('supplierterm_id', $paymentterms, old('paymentterm_id', $company->supplierterm_id),array('id' => 'paymentterm_id', 'class' => 'form-control bm-select select-with-icon'))}}		
						@if ($errors->has('supplierterm_id')) <p class="bg-danger">{{ $errors->first('paymentterm_id') }}</p> @endif
					</div>
				</div>
			@endif
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
			//lnkpaymentterm
			$("#lnkpaymentterm").bind('click', function(e) {
				e.preventDefault();
				//alert('aa');
				var table = document.getElementById('listtable');
				var rowLength = table.rows.length;
				if (rowLength > 1) {
					for (var i = 1; i <= rowLength - 1; i += 1){
						var row = table.rows[i];
						paymentterm = row.cells[0].innerHTML;
						if (paymentterm.includes($('#paymentterm_id option:selected').text()))  {
							if (row.style.display != 'none') {
								alert("Already assigned");
								return false;
							}										
						}
					}
				}	
				var row = '<tr>';							
				row = row + '<td>';
				row = row + $('#paymentterm_id option:selected').text();
				row = row + '<input name="pt_id[]" type="hidden" value="' + $('#paymentterm_id option:selected').val() + '">';
				row = row + '<input name="pt_name[]" type="hidden" value="' + $('#paymentterm_id option:selected').text() + '">';
				row = row + '</td>';
				row = row + '<td><input name="buyup[]" type="text" class="form-control"></td>';
				row = row + '<td>';							 
				row = row + '<input name="id[]" type="hidden" class="form-control">';
				row = row + '<input name="paymenttermdel[]" id="paymenttermdel" type="hidden" class="form-control">';
				row = row + '<a href="#" class="delete-icon" onclick="delpt(this);return false;" id="btnDel" type="button" title="Delete payment term"></a>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#listtable').append(row);
				$("#paymenttermcount").val(parseInt($("#paymenttermcount").val()) + 1);
			});
			//lnkpaymentterm end
		});
		function delpt(lnk) {
			var tr = lnk.parentNode.parentNode;
			var td = lnk.parentNode;
			var inputs = td.getElementsByTagName("input");	
			var inputslengte = inputs.length;
			for(var j = 0; j < inputslengte; j++){
				var inputval = inputs[j].id;                
				inputs[j].value  = 1;
				tr.cells[2].getElementsByTagName("input")[0].value='0';
			}
			tr.style.display = 'none';
		}
	</script>
@endpush 