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
	@if (isset($shippingaddress)) 
		{{ Form::model($shippingaddress, array('id' => 'frmManage', 'class' => isset($mode) ? 'tab-content' : 'form-horizontal' )) }}
	@else
		{{ Form::open(array('id' => 'frmManage', 'class' => isset($mode) ? 'tab-content' : 'form-horizontal' )) }}
	@endif
	<input name="company_id" id="company_id" type="hidden" value="{{ \Auth::user()->getCompanyId() }}">


		<div class="col-sm-6"> <!-- partyname -->
			<div class="form-group  {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- partyname -->  
				{{ Form::label('partyname', 'Ship To Party Name', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' ))  }}
				@if (isset($mode))	
					<div><span class='form-control-static'>{{ $shippingaddress->partyname }}</span></div>
				@else
				<div class="col-sm-12">
					{{ Form::text('partyname', old('partyname'), array('id' => 'partyname', 'class' => 'form-control')) }}			
					@if ($errors->has('partyname')) <p class="bg-danger">{{ $errors->first('partyname') }}</p> @endif
				</div>
				@endif
			</div> 
		</div> <!-- partyname end -->						
		<div class="col-sm-6"> <!-- address -->	
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- address -->  
				{{ Form::label('address', 'Ship To Party Address', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
				@if (isset($mode))	
					<div><span class='form-control-static'>{{ $shippingaddress->address }}</span></div>
				@else
				<div class="col-sm-12 ">
					{{ Form::text('address', old('address'), array('id' => 'address', 'class' => 'form-control')) }}			
					@if ($errors->has('address')) <p class="bg-danger">{{ $errors->first('address') }}</p> @endif
				</div>
				@endif
			</div> 
		</div> <!-- address end -->
		<div class="col-md-6">
			<div class="form-group text-input">
				{{ Form::label('Country_id', 'Ship To Party Country', array('class' => 'form-label')) }}
				@if (isset($mode))
						@if ($shippingaddress->city_id == 0)
							<p class='form-control-static'>{{ $shippingaddress->country_name }}</p>
						@else
							<p class='form-control-static'>{{ $shippingaddress->city->country->countryname }}</p>
						@endif					
					@else
					<div>
						<div class="col-sm-12">
							@if (isset($shippingaddress))
								<?php $cntry = $shippingaddress->city->country_id; ?>
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
						@if ($shippingaddress->city_id == 0)
							<p class='form-control-static'>{{ $shippingaddress->city_name }}</p>
						@else
							<p class='form-control-static'>{{ $shippingaddress->city->cityname }}</p>
						@endif					
					@else
					<div>
						<div class="col-sm-12">
							@if (isset($shippingaddress))
								<?php $city = $shippingaddress->city_id; ?>
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
		<div class="col-sm-6">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- country -->  
				{{ Form::label('delivery_country_id', 'Delivery Location Country', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				@if (isset($mode))
					@if ($shippingaddress->delivery_city_id == 0)
						<p class='form-control-static'>{{ $shippingaddress->delivery_country_name }}</p>
					@else
						<p class='form-control-static'>{{ $shippingaddress->deliverycity->country->countryname }}</p>
					@endif					
				@else
				<div>
					<div class="col-sm-12">		
	
						{{ Form::select('delivery_country_id', $countries, Input::old('delivery_country_id', $cntry),array('id' => 'select_country1',  'class' => 'form-control bm-select' , 'placeholder' => 'City','style' => 'width: 100%')) }}
					</div>
					<div class="col-sm-6">
					@if (Input::old('delivery_country_id') == '0')
						{{ Form::text('delivery_country_name', old('delivery_country_name'), array('id' => 'delivery_country_name', 'class' => 'form-control', 'placeholder' => 'Country name')) }}
					@else
						{{ Form::text('delivery_country_name', old('delivery_country_name'), array('id' => 'delivery_country_name', 'class' => 'form-control hidden', 'placeholder' => 'Country name')) }}
					@endif
					</div>
					@if ($errors->has('delivery_country_id')) <p class="bg-danger">{{ $errors->first('delivery_country_id') }}</p> @endif
					@if ($errors->has('delivery_country_name')) <p class="bg-danger">{{ $errors->first('delivery_country_name') }}</p> @endif
				</div>
				@endif
			</div>  
		</div>
		<div class="col-sm-6">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- city -->  
				{{ Form::label('delivery_city_id', 'Delivery Location City', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				@if (isset($mode))
					@if ($shippingaddress->delivery_city_id == 0)
						<p class='form-control-static'>{{ $shippingaddress->delivery_city_name }}</p>
					@else
						<p class='form-control-static'>{{ $shippingaddress->deliverycity->cityname }}</p>
					@endif					
				@else
				<div>
					<div class="col-sm-12">

						{{ Form::select('delivery_city_id', $cities, Input::old('delivery_city_id', $city),array('id' => 'select_city1',  'class' => 'form-control bm-select' , 'placeholder' => 'City','style' => 'width: 100%')) }}
					</div>
					<div class="col-sm-6">
						@if (Input::old('country_id') == '0')
							{{ Form::text('delivery_city_name', old('delivery_city_name'), array('id' => 'delivery_city_name', 'class' => 'form-control', 'placeholder' => 'City name')) }}
						@else
							{{ Form::text('delivery_city_name', old('delivery_city_name'), array('id' => 'delivery_city_name', 'class' => 'form-control hidden', 'placeholder' => 'City name')) }}
						@endif
					</div>
					@if ($errors->has('delivery_city_id')) <p class="bg-danger">{{ $errors->first('delivery_city_id') }}</p> @endif
					@if ($errors->has('delivery_city_name')) <p class="bg-danger">{{ $errors->first('delivery_city_name') }}</p> @endif
					<input name="selecteddeliverycity" id="selecteddeliverycity" type="hidden" value="{{ old('delivery_city_id', $city) }}">
				</div>
				@endif
			</div> 
		</div>
		
		
		<div class="col-sm-6">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- phone -->  
				{{ Form::label('phone', 'Phone', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $shippingaddress->phone }}</p>
				@else
				<div class="col-sm-12">				
					{{ Form::text('phone', Input::old('phone'), array('id' => 'phone', 'class' => 'form-control phone', 'placeholder' => '+')) }}		
					@if ($errors->has('phone')) <p class="bg-danger">{{ $errors->first('phone') }}</p> @endif
				</div>
				@endif
			</div> 
		</div><!-- phone end --> 
		<div class="col-sm-6">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- fax -->  
				{{ Form::label('fax', 'Fax', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label' )) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $shippingaddress->fax }}</p>
				@else
				<div class="col-sm-12">				
					{{ Form::text('fax', Input::old('fax'), array('id' => 'fax', 'class' => 'form-control phone', 'placeholder' => '+')) }}		
					@if ($errors->has('fax')) <p class="bg-danger">{{ $errors->first('fax') }}</p> @endif
				</div>
				@endif
			</div> 
		</div><!-- fax end -->	
		<div class="col-sm-6">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- email -->  
				{{ Form::label('email', 'Email', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $shippingaddress->email }}</p>
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
				{{ Form::label('po_box', 'PO Box', array('class' => isset($mode) ? 'control-label bm-label col-sm-4':'form-label')) }}
				@if (isset($mode))	
					<p class='form-control-static'>{{ $shippingaddress->po_box }}</p>
				@else
				<div class="col-sm-12">
					{{ Form::text('po_box', old('po_box'), array('id' => 'po_box', 'class' => 'form-control')) }}			
					@if ($errors->has('po_box')) <p class="bg-danger">{{ $errors->first('po_box') }}</p> @endif
				</div>
				@endif
			</div> 
		</div> <!-- po_box end -->
		
		<div class="col-sm-12">
			<div class="form-group {{ isset($mode) ? 'form-group--view' : 'text-input required'}}"> <!-- country -->  
				{{ Form::label('delivery_country_id', 'Inco terms', array('class' => 'form-label'	)) }}
				@if (isset($mode))
					<p class='form-control-static'>{{ $shippingaddress->incoterm->name }}</p>					
				@else
						<div class="col-sm-6">		
						{{ Form::select('incoterm_id', $incoterms,'',array('id' => 'incoterm','class' => 'form-control bm-select' , 'placeholder' => 'Inco Term','style' => 'width: 100%')) }}
						</div>
						@if ($errors->has('incoterm_id')) <p class="bg-danger">{{ $errors->first('incoterm_id') }}</p> @endif
				@endif
			</div> 
		</div><!-- incoterm -->
		
		
		
		@if (isset($mode))
		<div class="col-sm-12"> <!-- Column 1 -->
			<div class="form-group"> <!-- vat exempt -->  
				{{ Form::label('vat', 'VAT', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
				<p class='form-control-static'>
					@if ($shippingaddress->vatexempt)
						Pending VAT exempt approval
					@else
						@if ($shippingaddress->vat)
							Yes
						@else
							No
						@endif
					@endif
				</p>
			</div>
		</div>
		@else
		<div class="col-sm-12">
			<div class="form-group">
				<label class="bm-label col-offset-md-2 col-sm-3">Apply for VAT exemption</label>
				<div class="checkbox indirect-label col-sm-6">
					<label class="checkbox">
						<input class="bm-checkbox" type="checkbox" name="cbvat" id ="cbvat">
						<span class="checkmark"></span>
					</label>
				</div>
			</div> 
		</div>
		@endif
		@if (isset($mode))
			<div class="col-sm-12"> <!-- Column 1 -->
				<div class="form-group"> <!-- vat exempt -->  
					{{ Form::label('default', 'Default address', array('class' => 'bm-label col-offset-md-2 col-sm-3')) }}
					<p class='form-control-static'>
						@if ($shippingaddress->default)
							Yes
						@else
							No
						@endif
					</p>
				</div>
			</div> <!-- Column 1 end -->
		@else
			<div class="col-sm-12">
				<div class="form-group">
					<label class="bm-label col-offset-md-2 col-sm-3">Make as default shipping address</label>
					<div class="checkbox indirect-label col-sm-6">
						<label class="checkbox">
							@if (isset($shippingaddress) && $shippingaddress->default)
								<input class="bm-checkbox" type="checkbox" name="cbdefault" id ="cbdefault" checked>
							@else
								<input class="bm-checkbox" type="checkbox" name="cbdefault" id ="cbdefault">
							@endif
							<span class="checkmark"></span>
						</label>
					</div>
				</div> 
			</div>
		@endif
	<div class="row">	<!-- row 3 --> 
		<div class="col-sm-offset-11"> <!-- Column 1 -->
			@if (!isset($mode))
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'biz-button colored-default hidden')) }}
			<a href="" class="biz-button colored-default" id="lnksubmit">
				Save
			</a>
			@endif    
			
		</div> <!-- Column 1 end -->
	</div> <!--row 3 end -->
	{{ Form::close() }}
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			@if(isset($shippingaddress))
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
		var country =  $('#select_country').val();
		var country1 =  $('#select_country1').val();

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

        $('#select_country ').on('change', function () {
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
		
		$('#select_country1').on('change', function () {
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
                    $('#select_city1').empty();
                    $.each(response, function (index, city) {
                        var newOption = new Option(city, index, false, false);
                        $('#select_city1').append(newOption).trigger('change');
                    });
                },
            });
        });

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
			
			$("#select_country1").change(function(){
				Updatedeliverycity();
				if ($("#delivery_country_id").val() == 0) {
					$("#delivery_country_name").removeClass('hidden');
					$("#delivery_city_name").removeClass('hidden');
				} else {
					$("#delivery_country_name").addClass('hidden');
					$("#delivery_city_name").addClass('hidden');
				}
			}); // $("#delivery_country_id").change end
			$("#select_city_1").change(function(){
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
		});

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