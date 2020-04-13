@extends('layouts.app') 
@section('content')
<div class="row row-fluid">
    <div class="col-md-12">
        <div class="text-center col-md-offset-4 col-md-4">
            <h3 class="bm-msg-header teal" style="margin-top: 0">Coming soon</h3>
            <h4 class="blue" style="margin-top: 0; font-size: 20px"><strong>{{ $context }}</strong> is under development</h4>
            <span class="bm-circle"></span>
            <p class="bm-msg-details info">We are still working on it, please stay tuned</p>
        </div>
    </div>
</div>
@stop