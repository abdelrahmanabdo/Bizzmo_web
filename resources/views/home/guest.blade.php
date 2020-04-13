<main role="main">
    <section class="input-section flex-container">
        <div class="login-section__background"></div>
        <div class="input-form-container flex-container-col panel panel-default">
            <h3 class="input-form-container__heading">Hello!</h3>
            <form role="form" method="POST" action="{{ url('/login') }}" class="panel-body">
            {{ csrf_field() }}
            <div class="form-group input-container{{ $errors->has('email') || $errors->has('token_error') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control input-container__elem" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
				@if ($errors->has('token_error'))
                    <span class="help-block">
                        <strong>{{ $errors->first('token_error') }}</strong>
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
			{{ Form::hidden('id', Request::get('id'), array('id' => 'id')) }}
			{{ Form::hidden('token', Request::get('token'), array('id' => 'token')) }}
            <div class="form-horizontal input-container flex-container">
                <div class="radio">
                    <label class="checkbox">
                        <input id="remember" class="bm-checkbox"  name="remember" {{ old('remember') ? 'checked' : ''}} type="checkbox">			
                        <span class="checkmark"></span>
                        <span class="bm-sublabel">Remember me</span> 
                    </label>
                </div>
                <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot password?</a>
            </div>

            <div class="form-group flex-container input-btns-container">
                <button type="submit" class="guest-pg-default-btn wide-btn">Login</button>
                <a class="btn btn-link" href="/register">Register</a>
            </div>
        </form>
        </div>
    </section>
    <section class="features-section flex-container-col">
        <div class="features-section-title flex-container-col">
            <span class="features-section-text-container">
                <span class="guest-pg-text--xlarge">Yes!&nbsp;</span>
                <span class="features-section__text--normal">To the new era of</span>
            </span>
            <span class="features-section__text--large">business growth!</span>
        </div>
        <div class="features-section-items">
            <div class="features-section-item flex-container-col">
                <img class="features-section-img" src="{{ asset('images/unlimited-access.png') }}" alt="unlimited access">
                <p class="guest-pg-text--normal center">Unlimited access to buyers and suppliers from new markets</p>
            </div>
            <div class="features-section-item flex-container-col">
                <img class="features-section-img" src="{{ asset('images/easy-direct.png') }}" alt="easy direct">
                <p class="guest-pg-text--normal center">Easy, direct and secured transactions with flexible payment options</p>
            </div>
            <div class="features-section-item flex-container-col">
                <img class="features-section-img" src="{{ asset('images/no-delays.png') }}" alt="no delays">
                <p class="guest-pg-text--normal center">No delays and no paperwork! Faster payments covered by Bizzmo</p>
            </div>
        </div>
    </section>
    <section class="steps-section flex-container-col" style="background: #f7f7f7">
        <div class="steps-section__title flex-container-col">
            <span class="guest-pg-text--large">Expanding your business in</span>
            <span class="guest-pg-text--xlarge">2 easy steps</span>
        </div>
        <div class="steps flex-container">
            <div class="steps-item flex-container">
                <span class="steps-circle flex-container">
                    <span class="steps-item__num">1</span>
                </span>
                <span>
                    <span class="steps-item__text">Register as a user</span>
                    <br><span class="steps-item__text">& create your profile</span>
                </span>
            </div>
            <div class="steps-item flex-container">
                <span class="steps-circle flex-container">
                    <span class="steps-item__num">2</span>
                </span> 
                <span class="steps-item__text">Get introduced to potential business partners</span>
            </div>
        </div>
        <a class="guest-pg-default-btn" href="/how-it-works">Learn More</a>
    </section>
    @include('home.footer')
</main>
