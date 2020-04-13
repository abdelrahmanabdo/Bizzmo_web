{!! Form::open(array('id' => 'frmManageBuyers', 'action' => 'companycontroller@savebuyersajax', 'class' => 'my-buyer-modal')) !!}
@if (!isset($mode))
	<div class="row" style="margin-bottom: 15px">	<!-- row 1 -->
		<div class="col-sm-12"> <!-- column 3 -->
			<div class="form-group"> <!-- company -->  
				{!! Form::label('itemName', 'Buyer', array('class' => 'col-sm-12')) !!}
				<div class="col-sm-12">
					<select class="form-control myselect2" name="buyer_id_select" id="buyer_id_select"></select>
					<a href="" id="lnkassign" type="button"><span class="add-icon" title="Assign buyer to buyer"></span></a>					
					@if ($errors->has('buyer_id_select')) <p class="bg-danger">{!! $errors->first('buyer_id_select') !!}</p> @endif
				</div>
			</div> <!-- company end -->  
		</div> <!-- column 3 end -->
	</div> <!--row 1 end -->
@endif
<div class="row">	<!-- row 2 -->
	<div class="col-sm-12"> <!-- Column 1 -->
		<table id="listtable" class="table table-striped table-condenesed table-bordered table-hover" style="bottom: 10px;position: relative;">
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
						<td><input name="companyname[]" type="hidden" value="{{ old('companyname')[$i] }}">{{ old('companyname')[$i] }}</td>
						<td><input name="buyername[]" type="hidden" value="{{ old('buyername')[$i] }}">{{ old('buyername')[$i] }}</td>
						@if (!isset($mode))
							<td>
								<a href="" onclick="DelBuyer(this);return false;" id="btnDelProduct"><span class="delete-icon"></span></a>
								{{ Form::hidden('buyerid[]', old('buyerid')[$i], array('id' => 'buyerid')) }}
								{{ Form::hidden('del[]', old('del')[$i], array('id' => 'del')) }}
								{{ Form::hidden('newrow[]', old('newrow')[$i], array('id' => 'newrow', 'class' => 'newrow')) }}
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
							@php $buyerId = $buyer->id; @endphp
							<td>
								<a href="#" onclick="DelBuyer(this);return false;" id="btnDelProduct"><span class="delete-icon"></span></a>
								{{ Form::hidden('buyerid[]', $buyer->id, array('id' => 'buyerid')) }}
								{{ Form::hidden('del[]', '', array('id' => 'del')) }}
								{{ Form::hidden('newrow[]', '', array('id' => 'newrow', 'class' => 'newrow')) }}
							</td>
							@endif
					</tr>
					@endforeach
				@endif	
			</tbody>
		</table>
	</div> <!-- Column 1 end -->
</div> <!--row 2 end -->
<div class="row">	<!-- row 2 --> 
	<div class=" col-md-12"> <!-- column 1 -->
	@if (isset($mode))
		<div class="col-xs-6 col-sm-3"> <!-- column 3 -->
			<a href='{!! url("/companies/mybuyers") !!}' class="btn btn-warning fixedw_button" role="button" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
		</div>
	@else
		{!! Form::submit('Save', array('id' => 'submit_buyers', 'class' =>'btn btn-info fixedw_button bm-btn green hidden')) !!}
		<a href="" class="btn btn-info bm-btn green" id="lnksubmit_buyers" title="Save">
			Add
		</a>
	@endif 	
	</div> <!-- column 1 end -->
</div> <!--row 2 end -->
{{ Form::close() }}
@push('scripts')	
	<script type="text/javascript">

		$(document).ready(function(){
			$("#submit_buyers").hide();
			$("#lnksubmit_buyers").bind('click', function(e) {
				e.preventDefault();
				// $("#submit_buyers").click();
				saveBuyers();
			});

			//lnkassign click
			$("#lnkassign").bind('click', function(e) {
				e.preventDefault();
				var table = document.getElementById('listtable');
				var rowLength = table.rows.length;
				if ($("#buyer_id_select").val() == null) {
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
						if (inputs[0].value.trim() == $("#buyer_id_select").val()) {
							alert('This buyer is already assigned');
							return false;
						}
					}
				}
				// Remove empty row
				if ($('.dataTables_empty').length)
					$('.dataTables_empty').parent().remove();

				var row = '<tr>';					
				row = row + '<td><input name="buyername[]" type="hidden" value="' + $("#buyer_id_select option:selected").text() + '">' +  $("#buyer_id_select option:selected").text() + '</td>';
				row = row + '<td>';
				row = row + '<input name="buyerid[]" type="hidden" value="' + $("#buyer_id_select").val() + '">';
				row = row + '<input name="del[]" id="del" type="hidden">';
				row = row + '<input name="newrow[]" class="newrow" id="newrow" type="hidden" value="1">';
				row = row + '<a href="#" class="delete-icon" onclick="DelBuyer(this);return false;" id="btnDel" type="button"></a>';
				row = row + '</td>';
				row = row + '</tr>';
				$('#listtable').append(row);
			});
			//lnkassign click end
			//select2
			$('.myselect1').select2({
				placeholder: 'Select a buyer',
				minimumResultsForSearch: -1
			  });
			//select2 end
			//select2
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
			//select2 end
		});

		//Del Buyer
		function DelBuyer(lnk) {
			$(lnk).siblings('#del').val(1);
			var tr = $(lnk).parents('tr');
			tr.hide();
		}

		function saveBuyers() {
			var formData = $("#frmManageBuyers").serializeArray();
			$.ajax({					
				url: '/companies/savebuyersajax',
				type: 'POST',
				data: formData,
				dataType: 'JSON',
				success: function(response){
					$('#manageBuyersModal').modal('hide');

					// Remove all buyers
					$("select#company_id option").each(function() {
							$(this).remove();
					});

					// Remove hidden tr(s)
					$('#manageBuyersModal tr:hidden').remove();
					$('.newrow').each(function(item){
						$(this).val('');
					});

					// Add new buyers list
					var buyers = response;
					$.each(buyers, function (i, item) {
							$('select#company_id').append($('<option>', { 
									value: item.id,
									text : item.companyname 
							}));

							if (i==0) {
								$('select#company_id').val(item.id).trigger('change');
							}
					});

				},
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			})
		}
	</script>
@endpush