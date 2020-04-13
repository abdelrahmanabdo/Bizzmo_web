@extends('layouts.app')
@section('content')
<main role="main">
    <section class="input-section flex-container">
        <div class="register-section__background"></div>
        <div class="input-form-container flex-container-col panel panel-default">
            <h3 class="input-form-container__heading">New account</h3>
            <form role="form" method="POST" action="{{ url('/register') }}" class="panel-body">
            {{ csrf_field() }}
            <div class="form-group input-container{{ $errors->has('name') ? ' has-error' : '' }}">
                <input id="name" type="text" class="form-control input-container__elem" name="name" placeholder="Name" value="{{ old('name') }}" required autofocus>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group input-container{{ $errors->has('title') ? ' has-error' : '' }}">
                <input id="title" type="text" class="form-control input-container__elem" name="title" placeholder="Job title" value="{{ old('title') }}" required autofocus>
                @if ($errors->has('title'))
                    <span class="help-block">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group input-container{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control input-container__elem" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group input-container{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control input-container__elem" name="password" placeholder="Password" required>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group input-container">
                <input id="password-confirm" type="password" class="form-control input-container__elem" name="password_confirmation" placeholder="Re Enter Password" required>
            </div>
            <div class="form-group flex-container input-btns-container">
                <button type="submit" class="guest-pg-default-btn wide-btn">Register</button>
                <a class="btn btn-link" href="/">Go back</a>
            </div>
        </form>
        </div>
    </section>
</main>
@endsection
