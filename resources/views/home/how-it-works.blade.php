@extends('layouts.app')
@section('content')
<main role="main" class="row-fluid">
    <section class="hiw-section">
        <div class="hiw-section__background"></div>
        <div class="flex-container sub-section ">
            <div class="main-text col-sm-9">
                <h1 class="text--large">Follow these <span class="big">2 steps</span></h1>
                <h1 class="text--large col-md-offset-3 col-md-9 large-lh">to expand your business</h1>
            </div>
            <div class="col-md-3 btn-container">
                <a class="guest-pg-default-btn" href="/register">Register Now</a>
            </div>
        </div>
        <div class="flex-container sub-section">
            <div class="steps-item flex-container">
                <span class="steps-circle flex-container">
                    <span class="steps-item__num">1</span>
                </span>
                <span class="flex-container-col text-container">
                    <span class="steps-item__text">Register as a user & create your profile</span>
                    <span class="steps-item__text sm">Easily set up a company account as a supplier, buyer or both</span>
                </span>
            </div>
            <div class="steps-item flex-container">
                <span class="steps-circle flex-container">
                    <span class="steps-item__num">2</span>
                </span> 
                <span class="flex-container-col text-container">
                    <span class="steps-item__text">Get introduced to potential business partners</span>
                    <span class="steps-item__text sm">Await contact or initiate your business request & start your secured cooperations</span>
                </span>
            </div>
            <div class="btn-container--mobile">
                <a class="guest-pg-default-btn" href="/register">Register Now</a>
            </div>
        </div>
    </section>
    <section class="diagram-section">
        <div class="pre-diagram flex-container-col">
            <div class="text-container">
                <div class="pre-diagram-text first">Learn more about</div>
                <div class="pre-diagram-text scnd">the process</div>
            </div>
            <div class="arrow-down"></div>
        </div>
        <div class="diagram flex-container">
            <img src="{{ asset('images/diagram.png')}}" alt="how it works diagram">
        </div>
    </section>
    @include('home.footer')
</main>
@endsection

