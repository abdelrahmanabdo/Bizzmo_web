@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{ $title}}</div>

                <div id="code-sent-msg" class="text-info text-center" style="margin-bottom: 10px;margin-top: 10px">
                    Verification code has been sent to your phone {{ $phone }}
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('verificationCode') ? ' has-error' : '' }}">
                            <label for="verificationCode" class="col-md-3 control-label">Verification Code</label>

                            <div class="col-md-6">
                                <input id="verificationCode" type="text" class="form-control" name="verificationCode" value="{{ old('verificationCode') }}" autofocus />

                                @if ($errors->has('verificationCode'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('verificationCode') }}</strong>
                                    </span>
                                @endif

                                <div class="text-danger">
                                    {{ isset($error) ? $error : "" }}
                                </div>
                            </div>
                        </div>

                        <div class="bm-sublabel text-center" style="margin-bottom: 10px;margin-top: 10px">
							@if ($usertype == 'buyer')
								If you did not recieve a verification code please click <a href="/purchaseorders/resend/{{ $po_id }}" >here</a>
							@else
								If you did not recieve a verification code please click <a href="/purchaseorders/vresend/{{ $po_id }}" >here</a>
							@endif
                        </div>

                        <!-- Saving the value of the verification token -->
                        <input id="po_id" type="text" class="hidden form-control" name="po_id" value="{{ $po_id }}">
                        <input id="phone" type="text" class="hidden form-control" name="phone" value="{{ $phone }}">

                        <div class="form-group" style="display: flex;">
                            <div style="margin: auto">
                                <button id="verify-btn" type="submit" class="btn btn-primary bm-btn">
									@if ($usertype == 'buyer')
										Submit
									@else
										Accept
									@endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
