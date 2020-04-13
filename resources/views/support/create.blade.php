@if (Auth::guest())
    @extends('layouts.app')
    @section('content')
    <div class="row-fluid">
        <main role="main">
            <section class="support-section flex-container dark-bg">
                <div class="support-section__background"></div>
                <div class="support-section-content">
                    <div class="col-sm-12"><h1>Have more questions?</h1></div>
                    <div class="col-sm-12"><h2>We're here to help!</h2></div>
                </div>
            </section>
            <section class="support-input-section col-sm-offset-1 col-sm-10">
                <form role="form" method="POST" >
                    {{ csrf_field() }}
                    <div class="col-sm-12">
                        <div class="form-group col-sm-6{{ $errors->has('name') ? ' has-error' : '' }}">
                            <input id="name" type="text" class="form-control large-input" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6{{ $errors->has('title') ? ' has-error' : '' }}">
                            <input id="title" type="text" class="form-control large-input" name="title" placeholder="Job title" value="{{ old('title') }}" required autofocus>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group col-sm-6{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input id="email" type="email" class="form-control large-input" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group col-sm-6{{ $errors->has('company') ? ' has-error' : '' }}">
                            <input id="company" type="tel" class="company form-control large-input" name="company" placeholder="Company" maxlength="17" value="{{ old('company') }}" required>
                            @if ($errors->has('company'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('company') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group col-sm-12{{ $errors->has('message') ? ' has-error' : '' }}">
                            <textarea rows="3" id="message" class="form-control" name="message" placeholder="Your message" required>"{{ old('message') }}</textarea>
                            @if ($errors->has('message'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('message') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group flex-container input-btns-container">
                        <button type="submit" class="guest-pg-default-btn wide-btn">Send</button>
                    </div>
                </form>
            </section>
            @include('home.footer')
        </main>
    @endsection
@endif