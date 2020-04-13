@extends('layouts.app' )
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
	
@stop
@section('content')
{{ Form::open(array('id' => 'frmManage', 'class' => 'po-form')) }}
	<div class=" bm-pg-header">	<!-- row 1 -->
		<h2 class="bm-title">{{ $title }}</h2>
	</div>				<!-- end row 1 -->
	<div class="">	<!-- row 2 -->		
		<div class="col-md-6">  <!-- column 1 -->
			<div class="form-group text-input"> <!-- title -->  
				{{ Form::label('title', 'Title' , array('class'=>'form-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $chat->title }}</p>
				@else										
					@if (isset($purchaseorder))
						<p class='form-control-static'>{{ $purchaseorder->title }}</p>
						{{ Form::hidden('title', old('title'), array('id' => 'title')) }}
					@else
						{{ Form::text('title', old('title'), array('id' => 'title', 'class' => 'form-control')) }}			
					@if ($errors->has('title')) <p class="bg-danger">{{ $errors->first('title') }}</p> @endif
					@endif
					{{ Form::hidden('autotitle', old('autotitle'), array('id' => 'autotitle')) }}
				@endif
				@if (isset($purchaseorder) && isset($changes)  && $purchaseorder->isvendorchange)
					@if (array_key_exists('title', $changes))
						<p class="small bg-warning">vendor changed from: {{ $changes['title'] }} </p>
					@endif
				@endif
			</div>
		</div> <!-- column 1 end -->
		<div class="col-md-6">  <!-- column 2 -->
			<div class="form-group text-input"> <!-- user -->  
				{{ Form::label('user_id', 'Add Member', array('class'=>'form-label')) }}
				<div class="">
					<div class="flex-container">
						<?php
							if (old('user_id')) {
								if (old('user_id')) {
									$userID = old('user_id');
									$userName = old('selected_user_name') ; //$users[$userID];
								}
							} else {
								$userID = null;
								$userName = null;
							}
						?>
						<input name="selected_user_id" id="selected_user_id" type="hidden" class="form-control" value="{{ $userID }}">
						<input name="selected_user_name" id="selected_user_name" type="hidden" class="form-control" value="{{ $userName }}">
						{{ Form::select('user_id', [], $userName, array('class' => 'form-control select_user bm-select', 'style' => 'width: 100%')) }}
						@if ($errors->has('user_id')) <p class="bg-danger">{{ $errors->first('user_id') }}</p> @endif
						<a href="#" class="add-icon" id="lnkadd" role="button" alt="Add Member" title="Add Member" style="display: block;height:33px;width:33px;margin-left : 5px"></a>
					</div>	
				</div>
			</div> <!-- user --> 			
		</div> <!-- column 2 end -->
	</div> <!-- row 2 end -->
	<br>
	<div class="">	<!-- row 2 -->		
		<div class="table-wrapper col-md-12" style="margin-top:30px"> <!-- column 1 -->
			<?php
				$i = 0;
				$itemcount = 0; 
			?>
			<table id="itemstable" class="table table-striped table-bordered table-hover datatable">
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>Members</th>
					</tr>
				</thead>
				<tbody>
					@if (isset($chat))
					@else
						<tr>
							<td>
								{{ Form::hidden('itemid[]', Auth::user()->id, array('id' => 'item_id')) }}
								{{ Form::hidden('itemdel[]', '', array('id' => 'itemdel', 'class' => 'form-control')) }}
							</td>
							<td>{{ Auth::user()->name }}</td>
						</tr>
						<?php 
							$itemcount = 1;
						?>
					@endif
				</tbody>
			</table>			
			<input type="hidden" name="itemcount" id="itemcount" value="{{$itemcount}}">
		</div> <!-- column 1 end -->
	</div> <!-- row 3 end -->
	<div class="">	<!-- row 4 -->		
		<div class="col-md-12  biz-mt-3"> <!-- column 1 -->
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'biz-button colored-default hidden')) }}
			<a href="" class="biz-button colored-default green	" id="lnksubmit" type="button" title="Save">
				Save
				<span class="visible-xs visible-sm">Save</span>
			</a>
		</div> <!-- column 1 end -->
	</div> <!-- row 4 end -->	
{{ Form::close() }}	
@stop	
@push('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$("#lnksubmit").bind('click', function(e) {
			e.preventDefault();
			if ($("#itemcount").val() < 2) {
				alert("Cannot save. A chat must have at least two members.");
				return;
			}
			if ($("#itemcount").val() == '2') {
				var table = document.getElementById('itemstable');
				var rowLength = table.rows.length;
				for (var i = 1; i <= rowLength - 1; i += 1){
					var row = table.rows[i];
					if (row.style.display != 'none') {
						chatname = row.cells[1].innerHTML;
					}					
				}
				chattitle = document.getElementById('title');
				if (chattitle.value == '') {
					$('#title').val(chatname);
					$('#autotitle').val(1);
				}				
			}
			if ($("#title").val().trim() == '') {
				alert("Cannot save. Please enter chat title.");
				return;
			}
			//check if existing chat has same members
			var memberlist ='';
			var table = document.getElementById('itemstable');
			var rowLength = table.rows.length;
			for (var i = 1; i <= rowLength - 1; i += 1){
				var row = table.rows[i];
				if (row.style.display != 'none') {
					memberlist = memberlist + row.cells[0].getElementsByTagName("input")[0].value + ",";
				}					
			}
			
			var formData = new FormData;
			formData.append('memberlist', memberlist);
			formData.append('_token', $('input[name=_token]').val());
			$.ajax({					
				url: '/chat/members',
				type: 'POST',
				processData: false,
				contentType: false,
				cache: false,
				data: formData,
				dataType: 'text',
				success: function(response){
					//alert(response);
					console.log('cc' + response);	
					resp = '' + response;
					console.log('aa' + resp);				
					if (resp == '') {
						$("#submit").click();
					} else {
						if ($("#autotitle").val() == 1) {
							$('#title').val('');
							$('#autotitle').val('');
						}
						alert('Cannot save. Chat ' + response + ' exists with same members.');
						return false;
					}
				},
				error: function(e,a,b){
					//console.log(e,a,b);
				}
			});
			
			console.log('bb' + memberlist);
			//return false;
			//submit
			
		});			
		$('.select_user').each(function(i, elm) {
			$(elm).select2({
				placeholder: '',
				ajax: {
					url: '/chat/users/?_type=query',
					dataType: 'json',
					processResults: function (data) {
					return {
						results:  $.map(data, function (item) {
							return {
								text: item.name,
								id: item.id
							}
						})
					};
					},
					cache: true
				}
			});
			
			var user_id = $(elm).siblings('#selected_user_id').val();
			var user_name = $(elm).siblings('#selected_user_name').val();

			if ($(elm).find("option[value='" + user_name + "']").length) {
					$(elm).val(user_name).trigger('change');
			} else {
				// Create a DOM Option and pre-select by default
				var newOption = new Option(user_name, user_id, true, true);
				// Append it to the select
				$(elm).append(newOption).trigger('change');
			}
		})

		$("#user_id").change(function(){				
			$('#selected_user_id').val($('select[name=user_id]').val());
			$('#selected_user_name').val($('#user_id').children("option:selected").text());
		}); // $("#user_id").change end
		
		$("#lnkadd").bind('click', function(e) {
			if ($('#selected_user_id').val() == '') {
				alert("Please select a memeber");
				return;
			}
			var table = document.getElementById('itemstable');
			var rowLength = table.rows.length;
			if (rowLength >= 2) {
				for (var i = 1; i <= rowLength - 1; i += 1){
					var row = table.rows[i];
					username = row.cells[1].innerHTML;
					if (username == $('#selected_user_name').val())  {
						if (row.style.display != 'none') {
							alert("Selected user is already a member");
							return false;
						}										
					}
				}
			}
			
			e.preventDefault();
			var table = document.getElementById('itemstable');
			var rowLength = table.rows.length;
			var row = '<tr>';							
			row = row + '<td>';
			row = row + '<a href="#" class="delete-icon" onclick="DelRow(this);return false;" title="Delete member" style="margin-left: 4px" id="btnDelItem" type="button"></a>';
			row = row + '<input id="item_id" name="itemid[]" type="hidden" value="' + $('#selected_user_id').val() + '" class="form-control">';
			row = row + '<input name="itemdel[]" id="itemdel" type="hidden" class="form-control">';
			row = row + '</td>';
			row = row + '<td>' + $('#selected_user_name').val() + '</td>';
			row = row + '</tr>';
			$('#itemstable').append(row);
			$("#itemcount").val(parseInt($("#itemcount").val()) + 1);
		}); // $("#lnkadd").bind end
	});
	
	function DelRow(lnk) {
		var tr = lnk.parentNode.parentNode;
		var td = lnk.parentNode;
		var inputs = td.getElementsByTagName("input");	
		var inputslengte = inputs.length;
		for(var j = 0; j < inputslengte; j++){
				var inputval = inputs[j].id;         
				if (inputval == 'itemdel') {
					// New option
					inputs[j].value  = 1;
					$("#itemcount").val(parseInt($("#itemcount").val()) - 1);
				}
			}
		tr.style.display = 'none';
	}
</script>
@endpush