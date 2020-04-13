@extends('layouts.app', ['hideRightMenuAndExtend' => true]) 
@section('content')

@include('includes.support-nav')

<div class="bottom-space">
    {{-- <h2 class="bm-title">Report an isssue</h2> --}}
</div>
{{ Form::open(array('id' => 'frmManage')) }}
<div class="">
    <div class="col-sm-12">
        <div class="form-group col-sm-6 text-input required" style="padding-left: 0">
            <label class="form-label" for="title">Title</label>
            <input id="title" class="form-control" name="title" type="text" maxlength="60" value="{{ old('title') }}">
            @if ($errors->has('title')) <p class="bg-danger">{{ $errors->first('title') }}</p> @endif
        </div>
        <div class="form-group col-sm-6 text-input required" style="padding-right: 0">
            <label class="form-label" for="order_number">Order Number</label>
            <input id="order_number" class="form-control" name="order_number" type="text" maxlength="9" value="{{ old('order_number') }}">
            @if ($errors->has('order_number')) <p class="bg-danger">{{ $errors->first('order_number') }}</p> @endif
        </div>
    </div>
    <div class="form-group text-input col-sm-12 required">
        <label class="form-label" for="message">Description</label>
        <textarea id="message" class="form-control" name="message" type="text" rows="5" maxlength="255">{{ old('message') }}</textarea>
        @if ($errors->has('message')) <p class="bg-danger">{{ $errors->first('message') }}</p> @endif
    </div>
    <div class="col-sm-12">
        <div class="form-group text-input col-sm-6 required" style="padding-left: 0">
            <label class="form-label" for="comp_acc_info">Company Account Info</label>
            <textarea id="comp_acc_info" class="form-control" name="comp_acc_info" type="text" rows="4" maxlength="255">{{ old('comp_acc_info') }}</textarea>
            @if ($errors->has('comp_acc_info')) <p class="bg-danger">{{ $errors->first('comp_acc_info') }}</p> @endif
        </div>
        <div class="form-group text-input col-sm-6 required" style="padding-right: 0">
        <label class="form-label" for="supp_acc_info">Supplier Account Info</label>
            <textarea id="supp_acc_info" class="form-control" name="supp_acc_info" type="text" rows="4" maxlength="255">{{ old('supp_acc_info') }}</textarea>
            @if ($errors->has('supp_acc_info')) <p class="bg-danger">{{ $errors->first('supp_acc_info') }}</p> @endif
        </div>
    </div>
    <div class="row-fluid col-sm-6">
        <span class="red">*</span>
        <span class="bm-label"> denotes a required field.</span>
    </div>
    <div class="form-group flex-container col-sm-12 top-space">
        {{ Form::submit('Send', array('id' => 'submit', 'class' =>'biz-button colored-default')) }}
        <button type="button" id="clear_customer_supp_fields" class="btn btn--reset">Clear all</button>
    </div>
    {{ Form::close() }}
</div>
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			$("#clear_customer_supp_fields").bind("click", function(e){
                e.preventDefault();
                $("#frmManage")[0].reset();
			})
		});		
	</script>
@endpush
