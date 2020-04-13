@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
	{{ Form::open(array('id' => 'frmManage', 'class' => 'my-buyer-form')) }}
	{{ Form::hidden('company_id', $company->id, ['id' => 'company_id']) }}
	<div class="row-fluid bm-pg-header">	
		<h2 class="bm-pg-title">{{ $title}}</h2>
	</div>
	@if (!isset($mode))
	<div class="row-fluid">	
		<div class="col-md-6 col-xs-12"> 
			<div class="form-group"> 
				{!! Form::label('company_id', 'Supplier' ,['class' => 'label-view', 'style' => 'margin-bottom: 0']) !!}
				<p id="company_name" class="form-control-static" style="padding-bottom: 0">{{ $company->companyname }}</p>
			</div> 
		</div> 
		<div class="col-md-6 col-xs-12"> 
			<div class="form-group"> 
				{!! Form::label('itemName', 'Buyer', ['class' => 'd-block label-view']) !!}
				<select class="form-control myselect2 bm-select" name="buyer_id" id="buyer_id"></select>
				<a href="" id="lnkassign" type="button" class="add-icon" title="Assign buyer to supplier"></a>
				@if ($errors->has('buyer_id')) <p class="bg-danger">{!! $errors->first('buyer_id') !!}</p> @endif
			</div> 
		</div> 
	</div> 
	@endif
	<div class="row-fluid">	
		<div class="col-sm-12"> 
			<table id="listtable" class="table table-striped table-condenesed table-bordered table-hover">
				<thead>
					<tr>
						<th class="no-sort">Buyer</th>
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
								<td><input name="buyername[]" type="hidden" value="{{ old('buyername')[$i] }}">{{ old('buyername')[$i] }}</td>
								@if (!isset($mode))
								<td>
									<a href="" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
									{{ Form::hidden('buyerid[]', old('buyerid')[$i], array('id' => 'buyerid')) }}
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
						@foreach ($company->buyers->whereIn('companytype_id', [1, 3]) as $buyer)
							<tr>
								<td><input name="buyername[]" type="hidden" value="{{ $buyer->companyname }}">{{ $buyer->companyname }}</td>
								@if (!isset($mode))
								<td>
									<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDelProduct"></a>
									{{ Form::hidden('buyerid[]', $buyer->id, array('id' => 'buyerid')) }}
									{{ Form::hidden('del[]', '', array('id' => 'del')) }}
									{{ Form::hidden('newrow[]', '', array('id' => 'newrow')) }}
								</td>
								@endif
							</tr>
						@endforeach
					@endif	
				</tbody>
			</table>
		</div> 
	</div> 
	<div class="row-fluid">	
		<div class=" col-md-12"> 
		@if (isset($mode))
			<div class="col-xs-6 col-sm-3"> 
				<a href='{{ url("/companies/mybuyers") }}' class="btn bm-btn sun-flower fixedw_button" role="button" title="Edit"><span class="edit-icon-white"></span></a>
			</div>
		@else
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button hidden')) }}
			<a href="" class="btn bm-btn green" id="lnksubmit" title="Save">
				Save
			</a>
		@endif 	
		</div> 
	</div> 
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
				if ($("#buyer_id").val() == null) {
					alert('Please select a buyer');
					return
				}
				for (var i = 1; i < rowLength; i += 1) {
					var row = table.rows[i];
					// One row with one cell (empty table)
					if (!row.cells[1])
						break;
					var inputs = row.cells[1].getElementsByTagName("input");
					if (row.style.display != 'none' && inputs[0]) {
						if (inputs[0].value.trim() == $("#buyer_id").val()) {
							alert('This buyer is already assigned');
							return false;
						}
					}
				}
				// Remove empty row
				if ($('.dataTables_empty').length)
					$('.dataTables_empty').parent().remove();

				var row = '<tr>';					
				row = row + '<td><input name="buyername[]" type="hidden" value="' + $("#buyer_id option:selected").text() + '">' +  $("#buyer_id option:selected").text() + '</td>';
				row = row + '<td>';
				row = row + '<input name="buyerid[]" type="hidden" value="' + $("#buyer_id").val() + '">';
				row = row + '<input name="del[]" id="del" type="hidden">';
				row = row + '<input name="newrow[]" id="newrow" type="hidden" value="1">';
				row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" id="btnDel" type="button"></a>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#listtable').append(row);
			});

			$('.myselect2').select2({
				placeholder: 'Search for a buyer',
				ajax: {
				  url: '/companies/search-buyers',
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
		});
		
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
	</script>
@endpush