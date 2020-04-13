@extends('layouts.app') 
@section('content')
<div class="row bottom-space">
    <h2 class="bm-pg-title d-inline-block">{{ $issue->title }}</h2>
    @if($issue->isOpen())
    <h4 class="red d-inline-block va-super">(Open)</h4>
    @else
    <h4 class="green d-inline-block va-super">(Closed)</h4>
    @endif
</div>
@if($issue->company)
<div class="row">	<!-- row 2 -->		
    <div class="col-sm-6">  <!-- column 2 -->
        <div class="form-group"> <!-- company -->  
            {{ Form::label('company', 'company', ['class' => 'label-view']) }}
            <p class='form-control-static'>{{ $issue->company }}</p>
        </div> <!-- company end -->  
    </div>				<!-- end col 2 -->
</div>				<!-- end row 2 -->	
@endif
<div class="row">	<!-- row 3 --> 
    <div class="col-sm-12">  <!-- column 1 -->
        <div class="form-group"> <!-- status -->  
            {{ Form::label('message', 'Message', ['class' => 'label-view']) }}
            <p class='form-control-static'>{{ $issue->message }}</p>
        </div> <!-- status end -->  
    </div>					<!-- end col 1 -->	
</div>
@if($issue->comp_acc_info || $issue->supp_acc_info)
<div class="row">	<!-- row 2 -->		
    <div class="col-sm-6">  <!-- column 2 -->
        <div class="form-group"> <!-- company -->  
            {{ Form::label('comp_acc_info', 'Company Account Info', ['class' => 'label-view']) }}
            <p class='form-control-static'>{{ $issue->comp_acc_info }}</p>
        </div> <!-- company end -->  
    </div>
    <div class="col-sm-6">  <!-- column 2 -->
        <div class="form-group"> <!-- company -->  
            {{ Form::label('supp_acc_info', 'Supplier Account Info', ['class' => 'label-view']) }}
            <p class='form-control-static'>{{ $issue->supp_acc_info }}</p>
        </div> <!-- company end -->  
    </div>
</div>				<!-- end row 2 -->	
@endif
@if ($issue->resolution)
<div class="row">	<!-- row 4 --> 
    <div class="col-sm-12">  <!-- Column 1 -->
        <div class="form-group"> <!-- Role Name -->  
            {{ Form::label('resolution', 'Resolution', ['class' => 'label-view']) }}
            <p class='form-control-static'>{{ $issue->resolution }}</p>
        </div> <!-- Role name -->  
    </div>					<!-- end col 1 -->
</div>
@endif
@stop
