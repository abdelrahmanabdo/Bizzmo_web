@extends('layouts.app', ['hideRightMenuAndExtend' => true]) 
@section('content')
    @include('includes.support-nav')

    @if(isset($title))	
    <div class="">
        {{-- <h2 class="bm-title">{{ $title }}</h2> --}}
        <br/>
    </div>
    @endif
    <div class="">	<!-- row 4 -->
        <div class="col-sm-12"> <!-- column 1 -->
            <table id="listtable" class="table table-striped table-bordered table-hover table-condensed">
            <thead>
                <tr>
                    <th>Title</th>
                    <th style="max-width: 300px;">Message</th>
                    <th>Status</th>
                    <th class="no-sort" width="10%"></th>
                </tr>
            </thead>
            <tbody>			
                @foreach ($issues as $issue)
                <tr>
                    <td> {{ $issue->title }} </td>
                    <td class="text-truncate"> {{ $issue->message }} </td>
                    <td><span class="<?= $issue->isOpen() ? 'red' : 'green'?>"> {{ $issue->status_name }} </span></td>
                    <td>
                        <a href="{{ url("/support/view-issue/" . $issue->id) }}" role="button" class="view-icon" title="View"></a>
                    </td>
                </tr>	
                @endforeach			
            </tbody>
            </table>
        </div>
    </div>
@stop

