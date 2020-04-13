@extends('layouts.app' , ['hideRightMenu' => true , 'hideLeftMenu' => true])
@section('title')
	@if (isset($title))
		{{ $title }}
	@endif
@stop
@section('content')	
	<div class="form-container">
		<div class="row  form-header-container">
			<a href="javascript:history.go(-1)" class="back-arrow">
				<img src="{{asset('images/arrow-left.svg')}}" />
			</a>
			<div class="form-header-title-container">
				<h2 class="form-header-title">Create New Address</h2>
				<h4 class="form-header-hint"> You can easily add new address to your addresses .</h4>
			</div>
		</div>
		<div class="tabbable-panel">
		<div class="tab-content row">
				@if (isset($pickupaddress)) 
				{{ Form::model($pickupaddress, array('id' => 'frmManage', 'class' =>  isset($mode) ? 'tab-content' : 'form-horizontal' )) }}
			@else
				{{ Form::open(array('id' => 'frmManage', 'class' => isset($mode) ? 'tab-content' : 'form-horizontal' )) }}
			@endif
			<input name="company_id" id="company_id" type="hidden" value="{{ \Auth::user()->getCompanyId() }}">

			<div class="col-md-6"> <!-- address -->	
				<div class="form-group  {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- routing code -->  
					{{ Form::label('partyname', 'Pickup Party Name', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->partyname }}</p>

					@else
						{{ Form::text('partyname', old('partyname'), array('id' => 'partyname', 'class' => 'form-control')) }}			
						@if ($errors->has('partyname')) <p c	lass="bg-danger">{{ $errors->first('partyname') }}</p> @endif
					@endif
				</div>
			</div>
			<div class="col-md-6"> <!-- address -->	
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- address -->  
					{{ Form::label('address', 'Pickup Party Address', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->address }}</p>
					@else
						{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control')) }}			
						@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
					@endif
				</div> 
			</div> <!-- address end -->
			<div class="col-sm-6">
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- country -->  
					{{ Form::label('country_id', 'Pickup Party Country', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))
						@if ($pickupaddress->city_id == 0)
							<p class='form-control-static'>{{ $pickupaddress->country_name }}</p>
						@else
							<p class='form-control-static'>{{ $pickupaddress->city->country->countryname }}</p>
						@endif					
					@else
					<div>
						<div class="col-sm-12">
							@if (isset($pickupaddress))
								<?php $cntry = $pickupaddress->city->country_id; ?>
							@else
								<?php $cntry = ''; ?>
							@endif
							{{ Form::select('country_id', $countries, Input::old('country_id', $cntry), array('id' => 'select_country', 'class' => 'form-control bm-select' , 'placeholder' => 'Country','style' => 'width: 100%')) }}

						</div>
							@if (Input::old('country_id') == '0')
								{{ Form::text('country_name', old('country_name'), array('id' => 'country_name', 'class' => 'form-control', 'placeholder' => 'Country name')) }}
							@else
								{{ Form::text('country_name', old('country_name'), array('id' => 'country_name', 'class' => 'form-control hidden', 'placeholder' => 'Country name')) }}
							@endif
						@if ($errors->has('country_id')) <p class="bg-danger">{{ $errors->first('country_id') }}</p> @endif
						@if ($errors->has('country_name')) <p class="bg-danger">{{ $errors->first('country_name') }}</p> @endif
					</div>
					@endif
				</div> 
			</div><!-- country -->
			<div class="col-sm-6">
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}} "> <!-- city -->  
					{{ Form::label('city_id', 'Pickup Party City', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))
						@if ($pickupaddress->city_id == 0)
							<p class='form-control-static'>{{ $pickupaddress->city_name }}</p>
						@else
							<p class='form-control-static'>{{ $pickupaddress->city->cityname }}</p>
						@endif					
					@else
					<div>
						<div class="col-sm-12">
							@if (isset($pickupaddress))
								<?php $city = $pickupaddress->city_id; ?>
							@else
								<?php $city = ''; ?>
							@endif
							{{ Form::select('city_id', $cities, Input::old('city_id', $city), array('id' => 'select_city', 'class' => 'form-control bm-select' , 'placeholder' => 'City','style' => 'width: 100%')) }}

						</div>
						<div class="col-sm-12">
							@if (Input::old('country_id') == '0')
								{{ Form::text('city_name', old('city_name'), array('id' => 'city_name', 'class' => 'form-control', 'placeholder' => 'City name')) }}
							@else
								{{ Form::text('city_name', old('city_name'), array('id' => 'city_name', 'class' => 'form-control hidden', 'placeholder' => 'City name')) }}
							@endif
						</div>
						@if ($errors->has('city_id')) <p class="bg-danger">{{ $errors->first('city_id') }}</p> @endif
						@if ($errors->has('city_name')) <p class="bg-danger">{{ $errors->first('city_name') }}</p> @endif
						<input name="selectedcity" id="selectedcity" type="hidden" value="{{ old('city_id', $city) }}">
					</div>
					@endif
				</div> 
			</div><!-- city -->		
			
			<div class="col-md-6">
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- phone -->  
					{{ Form::label('phone', 'Phone', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->phone }}</p>
					@else
					<div class="col-sm-12">				
						{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => 'phone')) }}		
						@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
					</div>
					@endif
				</div> 
			</div><!-- phone end --> 
			<div class="col-md-6">
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}} "> <!-- fax -->  
					{{ Form::label('fax', 'Fax', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->fax }}</p>
					@else
					<div class="col-sm-12">				
						{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => 'Fax')) }}		
						@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
					</div>
					@endif
				</div> 
			</div><!-- fax end -->	
			<div class="col-sm-6">
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- email -->  
					{{ Form::label('email', 'Email', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->email }}</p>
					@else
					<div class="col-sm-12">				
						{{ Form::text('email', Input::old('email'), array('id' => 'email', 'class' => 'form-control')) }}		
						@if ($errors->has('email')) <p class="bg-danger">{{ $errors->first('email') }}</p> @endif
					</div>
					@endif
				</div> 
			</div><!-- email end -->	
			<div class="col-sm-6"> <!-- po_box -->	
				<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- po_box -->  
					{{ Form::label('po_box', 'PO Box', array('class' =>  isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
					@if (isset($mode))	
						<p class='form-control-static'>{{ $pickupaddress->po_box }}</p>
					@else
					<div class="col-sm-12">
						{{ Form::text('po_box', old('po_box'), array('id' => 'po_box', 'class' => 'form-control')) }}			
						@if ($errors->has('po_box')) <p class="bg-danger">{{ $errors->first('po_box') }}</p> @endif
					</div>
					@endif
				</div> 
			</div> <!-- po_box end -->		
			
			@if (isset($mode))
				<div class="col-sm-12"> <!-- Column 1 -->
					<div class="form-group"> <!-- vat exempt -->  
						{{ Form::label('default', 'Default address', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
						<p class='form-control-static'>
							@if ($pickupaddress->default)
								Yes
							@else
								No
							@endif
						</p>
					</div>
				</div> <!-- Column 1 end -->
			@else
				<div class="col-sm-12" style="display:flex ; flex-direction : row ; justify-content : space-between ; align-items:center">
					<div>
						<label class=" col-sm-10">Make as default pickup address</label>
						<div class="checkbox indirect-label col-sm-2">
							<label class="checkbox">
								@if (isset($pickupaddress) && $pickupaddress->default)
									<input class="bm-checkbox" type="checkbox" name="cbdefault" id ="cbdefault" checked>
								@else
									<input class="bm-checkbox" type="checkbox" name="cbdefault" id ="cbdefault">
								@endif							
								<span class="checkmark"></span>
							</label>
						</div>
					</div> 
					<div> <!-- Column 1 -->
						@if (!isset($mode))
						{{ Form::submit('Save', array('id' => 'submit', 'class' =>'biz-button colored-default hidden')) }}
						<a href="" class="biz-button colored-default" id="lnksubmit">
							Save
						</a>
						@endif    
						
					</div> <!-- Column 1 end -->
				</div>
			@endif
		{{ Form::close() }}
		</div>
			
		</div>
	</div>
	@stop
	@push('scripts')	
		<script type="text/javascript">
			$(document).ready(function(){
				@if(isset($pickupaddress))
					$('.text-input').addClass('focused');
					$('.select2-selection.select2-selection--single').addClass('focused');
				@endif
				var phone = document.getElementById("phone");
				var fax = document.getElementById("fax");
				if (phone) {
					//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(phone);
					Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(phone);
				}
				if (fax) {
					//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(fax);
					Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(fax);
				}
	
				Updatecity();
				Updatedeliverycity();
				$("#lnksubmit").bind('click', function(e) {
					e.preventDefault();
					$("#submit").click();
				});	
				$("#country_id").change(function(){
					Updatecity();
					if ($("#country_id").val() == 0) {
						$("#country_name").removeClass('hidden');
						$("#city_name").removeClass('hidden');
					} else {
						$("#country_name").addClass('hidden');
						$("#city_name").addClass('hidden');
					}
				}); // $("#country_id").change end
				$("#city_id").change(function(){
					$("#selectedcity").val($('select[name=city_id]').val());
				}); // $("#city_id").change end
				
				$("#delivery_country_id").change(function(){
					Updatedeliverycity();
					if ($("#delivery_country_id").val() == 0) {
						$("#delivery_country_name").removeClass('hidden');
						$("#delivery_city_name").removeClass('hidden');
					} else {
						$("#delivery_country_name").addClass('hidden');
						$("#delivery_city_name").addClass('hidden');
					}
				}); // $("#delivery_country_id").change end
				$("#delivery_city_id").change(function(){
					$("#selecteddeliverycity").val($('select[name=delivery_city_id]').val());
				}); // $("#delivery_city_id").change end
				
				
				//validation
				$("#frmManage1").validate({
				rules: {
					name: {
					required: true,
					maxlength: 60
					},
					buyup: {
					required: true,
					number: true
					}
				},	
				messages: {
					name: "Length between 1 and 60",
					buyup: "Fees must be numeric"
				}
				});		
				

				var country =  $('#select_country').val();
        var selectedCity =  $('#select_city').val();
            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        if(selectedCity == index)
                        {
                            $('#select_city').append($('<option></option>').val(index).html(city).attr("selected", true));
                        }else{
                            $('#select_city').append($('<option></option>').val(index).html(city));
                        }
                    });
                },
            });

        $('#select_country').on('change', function () {
            var country = $(this).val();

            $.ajax({
                url: '/countries/cities',
                method: "POST",
                cache: false,
                data: {
						'country_id':country,
						'_token': $('input[name=_token]').val()
					},
                success: function (response) {
                    $('#select_city').empty();
                    $.each(response, function (index, city) {
                        var newOption = new Option(city, index, false, false);
                        $('#select_city').append(newOption).trigger('change');
                    });
                },
            });
        });	
			});
			function Updatecity () {
				var url = '/countries/cities';
					// ajax call
					$('#city_id').find('option').remove().end();
					if ($("#country_id").val() == 0) {
						$('#city_id').append($("<option></option>").attr("value", "0").text("Other").attr("selected", true));
						return true;
					}
					$.ajax({
						url: url,
						type:'post',
						data: {
							'country_id':$('select[name=country_id]').val(),
							'_token': $('input[name=_token]').val()
						},
						cache: false,
						success: function(data){
							var j = 0;
							var selectedcity = $("#selectedcity").val();
							$.each(data, function(i, item) {
								if (j == 0 || selectedcity == i) {
									$('#city_id').append($("<option></option>").attr("value", i).text(item).attr("selected", true));
								} else {
									$('#city_id').append($("<option></option>").attr("value", i).text(item)).attr("selected", false);
								}
								j = j + 1;							
							});
						}, // End of success function of ajax form
						error: function(output_string){				
							alert(jxhr.responseText);
						}
					}); //ajax call end
			}
			function Updatedeliverycity () {
				var url = '/countries/cities';
					// ajax call
					$('#delivery_city_id').find('option').remove().end();
					if ($("#delivery_country_id").val() == 0) {
						$('#delivery_city_id').append($("<option></option>").attr("value", "0").text("Other").attr("selected", true));
						return true;
					}
					$.ajax({
						url: url,
						type:'post',
						data: {
							'country_id':$('select[name=delivery_country_id]').val(),
							'_token': $('input[name=_token]').val()
						},
						cache: false,
						success: function(data){
							var j = 0;
							var selectedcity = $("#selecteddeliverycity").val();
							$.each(data, function(i, item) {
								if (j == 0 || selectedcity == i) {
									$('#delivery_city_id').append($("<option></option>").attr("value", i).text(item).attr("selected", true));
								} else {
									$('#delivery_city_id').append($("<option></option>").attr("value", i).text(item)).attr("selected", false);
								}
								console.log(j);
								j = j + 1;							
							});
						}, // End of success function of ajax form
						error: function(output_string){				
							alert(jxhr.responseText);
						}
					}); //ajax call end
			}
		</script>
	@endpush
	<style>
		.content .tab-content {
			margin: 24px !important;
		}
		.tab-content {
			padding: 24px 0px !important;
		}
	</style>