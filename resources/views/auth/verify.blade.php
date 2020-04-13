@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Verify your account</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="/register/send-verification-code">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('verificationCode') ? ' has-error' : '' }}">
                            <label for="verificationCode" class="col-md-3 control-label">Mobile</label>

                            <div class="col-md-6">
                                @if (isset($phone))
                                    <input id="phone" type="text" class="form-control mobile" name="phone" value="{{ $phone }}" autofocus placeholder="+00000000000000">
                                @else
                                    <input id="phone" type="text" class="form-control mobile" name="phone" value="{{ old('phone') }}" autofocus placeholder="+00000000000000">
                                @endif

                                @if ($errors->has('phone'))
                                    <span class="help-block red">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Saving the value of the verification token -->
                        <input id="verificationToken" type="text" class="hidden form-control" name="verificationToken" value="{{ $verificationToken }}">

                        <div class="form-group" style="display: flex;">
                            <div style="margin: auto">
                                <button id="verify-btn" type="submit" class="btn btn-primary bm-btn">
                                    Send code
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@push('scripts')	
	<script type="text/javascript">
		$(document).ready(function(){
			var phone = document.getElementById("phone");
			if (phone) {
				//Inputmask({"regex": "\\+\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+[-|\\s]\\d+", "placeholder": ""}).mask(phone);
                Inputmask({"regex": "\\+\\d+", "placeholder": ""}).mask(phone);
			}
        });
    </script>
@endpush
