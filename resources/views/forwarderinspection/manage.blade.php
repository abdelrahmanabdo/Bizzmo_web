@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('styles')
@stop
@section('content')
<div class="row bm-pg-header">
		<h2 class="bm-pg-title">Inspection</h2>
	</div>
    {{ Form::open(array('id' => 'frmManage', 'class' => '')) }}
    <div class="row">
        <div class="form-group">
            <label>Template Name: </label>
            @if (isset($mode))
                <input id="templatename" name="templatename" class="form-control" placeholder="Template Name" value="{{$CompanyInspection->name}}" />
            @else
                <input id="templatename" name="templatename" class="form-control" placeholder="Template Name" />
            @endif
        </div>
        @if (isset($mode))
            {{ Form::hidden('ispection_id', $CompanyInspection->id, array('ispection_id' => 'ispection_id', 'class' => 'form-control')) }}
        @endif
        {{ Form::hidden('company_id', $company_id, array('company_id' => 'company_id', 'class' => 'form-control')) }}
		<table id="tblFeilds" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th><a id="btnAddRow" href="#" role="button" title="Add" class="add-icon"></a></th>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Required</th>
                    <th>Active</th>
                </tr>
            </thead>
            <tbody>
            @if (isset($mode))
                @foreach ($Inspections as $Inspection)
                <tr>
                <td><span class="delete-icon" type="button"></span></td>
                <td><input type="text" name="fieldname[]" id="fieldname[]" value="{{$Inspection->name}}"></td>
                <td>
                    <select name="fieldtype[]" id="fieldtype[]">
                        @if($Inspection->type=="Text")
                            <option selected>Text</option>
                        @else
                            <option>Text</option>
                        @endif
                        @if($Inspection->type=="Drop down")
                            <option selected>Drop down</option>
                        @else
                            <option>Drop down</option>
                        @endif
                    </select>
                </td>
                <td><input type="text" name="fieldvalue[]" id="fieldvalue[]" value="{{$Inspection->value}}"></td>
                <td>
                    @if($Inspection->required)
                        <input type="checkbox" name="chkRequired[]" id="chkRequired[]" checked>
                    @else
                        <input type="checkbox" name="chkRequired[]" id="chkRequired[]">
                    @endif
                </td>
                <td>
                    @if($Inspection->required)
                        <input type="checkbox" name="chkActive[]" id="chkActive[]" checked>
                    @else
                        <input type="checkbox" name="chkActive[]" id="chkActive[]">
                    @endif
                </td>
            </tr>
                @endforeach
            @else
            <tr>
            <td><span class="delete-icon" type="button"></span></td>
                <td><input type="text" name="fieldname[]" id="fieldname[]"></td>
                <td>
                    <select name="fieldtype[]" id="fieldtype[]">
                        <option>Text</option>
                        <option>Drop down</option>
                    </select>
                </td>
                <td><input type="text" name="fieldvalue[]" id="fieldvalue[]"></td>
                <td><input type="checkbox" name="chkRequired[]" id="chkRequired[]"></td>
                <td><input type="checkbox" name="chkActive[]" id="chkActive[]"></td>
            </tr>
		    @endif
            </tbody>
        </table>
        <input id="btnSave" type="submit" class="btn btn-default prev-step bm-btn" value="Save" />
		<!-- @if (isset($mode))
			{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal co-form', 'files' => true)) }}
			{{ Form::hidden('id', $ispection_id, array('id' => 'id', 'class' => 'form-control')) }}

			@if (isset($Forwarderroutes))
				{{ Form::text('forwarderroute', $Forwarderroutes->name, array('id' => 'forwarderroute', 'class' => 'form-control')) }}
			@else
				{{ Form::text('forwarderroute', Input::old('forwarderroute'), array('id' => 'forwarderroute', 'class' => 'form-control')) }}
			@endif
			{{ Form::submit('Save', array('id' => 'submit', 'class' =>'btn btn-primary fixedw_button ')) }}
			{{ Form::close() }}
		@else
			@if (isset($Forwarderroutes))
				{{$Forwarderroutes->name}}
			@endif
		@endif -->
	</div>
@stop
@push('scripts')
    <script type="text/javascript">
        // $("#btnSave").click(function (e) {
        //     e.preventDefault();
        // });

        $("#btnAddRow").click(function (e) {
            e.preventDefault();
            $("#tblFeilds > tbody").append('<tr><td><span class="delete-icon" type="button"></span></td><td><input type="text" name="fieldname[]" id="fieldname[]"></td><td><select name="fieldtype[]" id="fieldtype[]"><option>Text</option><option>Drop down</option></select></td><td><input type="text" name="fieldvalue[]" id="fieldvalue[]"></td><td><input type="checkbox"name="chkRequired[]" id="chkRequired[]"></td><td><input type="checkbox" name="chkActive[]" id="chkActive[]"></td></tr>');
        });

        $('#tblFeilds tbody').on('click', 'tr', function (e) {
            // e.preventDefault();
            if (!$(e.target).hasClass("delete-icon"))
                return;
            if (!confirm("Are you sure you want to delete line?")) {
                return;
            }
            var rowIndex = $(this).index();
            var textData = $(this).find("td").eq(0).text();
            $("#tblFeilds > tbody > tr:eq(" + rowIndex + ")").remove();
            // $.ajax({
            //     type: "POST",
            //     url: "ExtraFields.aspx/DeleteItem",
            //     contentType: "application/json; charset=utf-8",
            //     dataType: "json",
            //     data: '{Id:' + id + ', textData:"' + textData + '"}',
            //     success: function (response) {
            //         if (response.d == "OK") {
            //             $("#tblFeilds > tbody > tr:eq(" + rowIndex + ")").remove();
            //         }
            //         $('#MainUpdateProgress').hide();
            //     },
            //     error: function (jqXHR, textStatus, errorThrown) {
            //         console.log(jqXHR.responseText);
            //         if (jqXHR.status == 401) {
            //             $(location).attr('href', "/");
            //             return;
            //         }
            //         $('#MainUpdateProgress').hide();
            //     }
            // });
        });
    </script>
@endpush
