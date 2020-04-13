@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	@include('includes.companies-nav')

	{{ Form::open(array('id' => 'frmManage', 'class' => 'my-supplier-form')) }}
	{{ Form::hidden('company_id', $buyer_company->id, ['id' => 'company_id']) }}
	<div class="row-fluid bm-pg-header">	<!-- row 1 -->
		<h2 class="bm-title">{{ $title}}</h2>
	</div>
	@if (!isset($mode))
	<div class="row-fluid">	<!-- row 1 -->
		<div class="col-md-6 col-xs-12"> <!-- column 1 -->
			<div class="form-group"> <!-- company -->  
				{!! Form::label('company_id', 'Buyer' ,['class' => 'label-view', 'style' => 'margin-bottom: 0']) !!}
				<p id="company_name" class="form-control-static" style="padding-bottom: 0">{{ $buyer_company->companyname }}</p>
			</div> <!-- company end -->  
		</div> <!-- column 1 end -->
		<div class="col-md-6 col-xs-12"> <!-- column 3 -->
			<div class="form-group"> <!-- company -->  
				{!! Form::label('itemName', 'Supplier', ['class' => 'd-block label-view']) !!}
				<select class="form-control myselect2 bm-select" name="vendor_id" id="vendor_id"></select>
				<a href="" id="lnkassign" type="button" class="add-icon" title="Assign supplier to buyer"></a>
				@if ($errors->has('vendor_id')) <p class="bg-danger">{!! $errors->first('vendor_id') !!}</p> @endif
			</div> <!-- company end -->  
		</div> <!-- column 3 end -->
	</div> <!--row 1 end -->
	@endif
	<div class="row-fluid">	<!-- row 2 -->
		<div class="col-sm-12"> <!-- Column 1 -->
			<table id="listtable" class="table table-striped table-condenesed table-bordered table-hover">
				<thead>
					<tr>
						<th class="no-sort">Supplier</th>
						@if (!isset($mode))
						<th class="no-sort">				
						</th>	
						@endif
					</tr>		
				</thead>
				<tbody>
					@if (old('del'))
						@php
							$i = 0;
						@endphp
						@foreach (old('del') as $item)
							<tr style="{{ (old('del')[$i]) ? 'display:none' : '' }}">
								<td><input name="vendorname[]" type="hidden" value="{{ old('vendorname')[$i] }}">{{ old('vendorname')[$i] }}</td>
								@if (!isset($mode))
								<td>
									<a href="" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
									{{ Form::hidden('vendorid[]', old('vendorid')[$i], array('id' => 'vendorid')) }}
									{{ Form::hidden('del[]', old('del')[$i], array('id' => 'del')) }}
									{{ Form::hidden('newrow[]', old('newrow')[$i], array('id' => 'newrow')) }}
								</td>
								@endif
							</tr>
								@php
									$i++;
								@endphp	
						@endforeach
					@else
						@foreach ($buyer_company->vendors->whereIn('companytype_id', [2, 3]) as $vendor)
							<tr>
								<td><input name="vendorname[]" type="hidden" value="{{ $vendor->companyname }}">{{ $vendor->companyname }}</td>
								@if (!isset($mode))
								<td>
									<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
									{{ Form::hidden('vendorid[]', $vendor->id, array('id' => 'vendorid')) }}
									{{ Form::hidden('del[]', '', array('id' => 'del')) }}
									{{ Form::hidden('newrow[]', '', array('id' => 'newrow')) }}
								</td>
								@endif
							</tr>
						@endforeach
					@endif	
				</tbody>
			</table>
		</div> <!-- Column 1 end -->
	</div> <!--row 2 end -->
	<div class="row-fluid">	<!-- row 2 --> 
		<div class=" col-md-12"> <!-- column 1 -->
		@if (isset($mode))
			<div class="col-xs-6 col-sm-3"> <!-- column 3 -->
				<a href='{{ url("/companies/mysuppliers") }}' class="btn bm-btn sun-flower fixedw_button" role="button" title="Edit"><span class="edit-icon-white"></span></a>
			</div>
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn bm-btn green" id="lnksubmit" title="Save">
				Save
			</a>
		@endif 	
		</div> <!-- column 1 end -->
	</div> <!--row 2 end -->
@stop	
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#submit").hide();
			$("#lnksubmit").bind('click', function(e) {
				e.preventDefault();
				$("#submit").click();
			});
			//lnkassign click
			$("#lnkassign").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('listtable');
				var rowLength = table.rows.length;
				if ($("#vendor_id").val() == null) {
					alert('Please select a supplier');
					return
				}
				for (var i = 1; i < rowLength; i += 1) {
					var row = table.rows[i];
					// One row with one cell (empty table)
					if (!row.cells[1])
						break;
					var inputs = row.cells[1].getElementsByTagName("input");
					if (row.style.display != 'none' && inputs[0]) {
						if (inputs[0].value.trim() == $("#vendor_id").val()) {
							alert('This supplier is already assigned');
							return false;
						}
					}
				}
				// Remove empty row
				if ($('.dataTables_empty').length)
					$('.dataTables_empty').parent().remove();

				var row = '<tr>';					
				row = row + '<td><input name="vendorname[]" type="hidden" value="' + $("#vendor_id option:selected").text() + '">' +  $("#vendor_id option:selected").text() + '</td>';
				row = row + '<td>';
				row = row + '<input name="vendorid[]" type="hidden" value="' + $("#vendor_id").val() + '">';
				row = row + '<input name="del[]" id="del" type="hidden">';
				row = row + '<input name="newrow[]" id="newrow" type="hidden" value="1">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel" type="button"></a>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#listtable').append(row);
			});
			//lnkassign click end
			//select2
			// $('.myselect1').select2({
			// 	placeholder: 'Select a buyer',
			// 	minimumResultsForSearch: -1
			//   });
			//select2 end
			//select2
			$('.myselect2').select2({
				placeholder: 'Search for a supplier',
				ajax: {
				  url: '/companies/select2',
				  dataType: 'json',
				  delay: 250,
				  processResults: function (data) {
					return {
					  results:  $.map(data, function (item) {
							return {
								text: item.companyname,
								id: item.id
							}
						})
					};
				  },
				  cache: true
				}
			  });
			//select2 end
		});
		//DelRow
			function DelRow(lnk) {
				var tr = lnk.parentNode.parentNode;
				var td = lnk.parentNode;
				var inputs = td.getElementsByTagName("input");	
				var inputslengte = inputs.length;
				for(var j = 0; j < inputslengte; j++){
						var inputval = inputs[j].id;                
						if (inputval == 'del') {
							inputs[j].value  = 1;
						}
					}
				tr.style.display = 'none';
			}
			//DelRow end
	</script>
@endpush