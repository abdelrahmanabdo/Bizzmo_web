@extends('layouts.app')
@section('title')
	@if (isset($title))
	{{ $title }}
	@endif
@stop
@section('content')
		{{ Form::open(array('id' => 'frmManage', 'class' => 'form-horizontal')) }}
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Delivery ID</th>
				<th>Date</th>
				<th>File Name</th>
				<th>Attach</th>
			</tr>
		</thead>
		<tbody>
		@if(isset($result))
			@foreach ($result as $po)
				<tr>
					<td>{{$po->delivery_job_id}}</td>					
					<td>{{date('j/n/Y', strtotime($po->signed_at))}}</td>
					<td>{{$po->filename}}</td>
					<td>
					<div>
						<a href="" id="lnkitem" role="button" class="add-icon" title="Add Image" style="margin-left: 2px"></a>
						<input name="fileup" id="fileup" type="file" class="uploadclass" accept=".png, .jpg, .jpeg, .pdf" style="display: none" />
						<progress id="fprog" class="hidden" max="100"></progress>
						<input type="hidden" name="imgsrc" id="imgsrc" value = "">
						<input type="hidden" name="attid" id="attid" value = "{{$po->id}}">
						</div>
					</td>
				</tr>
			@endforeach
		@endif
		</tbody>
	</table>
@stop
@push('scripts')
<script type="text/javascript">
$(".add-icon").bind('click', function(e) {
		e.preventDefault();
		var div = e.target.parentNode;
		var inputs = div.getElementsByTagName("input");
		inputs[0].click();
	});
$('.uploadclass').on('change', (event) => {
		//alert('aa');
        var inputElem = event.target;
        var file = inputElem.files[0];

        type_is_valid = checkFileType(file.type);
        if (!type_is_valid)
        	return false;

        size_is_valid = checkFileSize(file.size);
        if(!size_is_valid)
        	return false;

		var div = event.target.parentNode;
		//console.log(div.childNodes);
		//var prg = div.childNodes[4];
		prg = document.getElementById("fprog");
		var img = div.childNodes[0];
		var imgsrc = div.childNodes[6];
		//var attid = div.childNodes[8];
		attid = document.getElementById("attid");
		//console.log(img);
		//console.log(prg);
		//console.log(imgsrc);
		prg.className ='';
		prg.value=0;

        // pendingFileUpload();
		console.log(attid.value);
        var formData = new FormData;
        formData.append('attach', file);
		formData.append('attid', attid.value);
        formData.append('_token', $('input[name=_token]').val());

        var ajax = new XMLHttpRequest();
		ajax.upload.addEventListener("progress", function(e){progressHandler(e, prg); }, false);

        //ajax.addEventListener("load", completeHandler, false);
        ajax.addEventListener("load", function(e){UploadcompleteHandler(e, prg, img, imgsrc); }, false);


        ajax.open("POST", "/tookandocupload");
        ajax.send(formData);
    });
	function progressHandler(event, prg) {
        //console.log('as' + event.loaded);
        var percent = Math.round((event.loaded / event.total) * 100);
		console.log(percent);
        prg.value = percent;
    }

    function UploadcompleteHandler(event, prg, img, imgsrc) {
		// return;
		prg.className ='hidden';
		//console.log(event.target.response);
		let obj = JSON.parse(event.target.response);
		//console.log(obj.path);
		//return;
		//prg.value = 0;
        // if($('#images').val().length==0){
        //     $('#images').val(obj.id);
        // }else{
        //     $('#images').val($('#images').val() + ',' + obj.id);
        // }
		img.src="/" + obj.path;
		imgsrc.value = "/" + obj.path;
        // UploadcompleteHandler();
		location.reload(); 
    }
	function checkFileSize(fileSize, maxsize = 8388608) {
        if (fileSize > maxsize) {
            alert('Maximum file size should be ' + parseInt(maxsize / 1000000) + 'M');
            return false;
        }
        return true;
    }

    function checkFileType(fileType) {
        if (fileType == '') {
            var plainType = '';
        } else {
            var plainType = fileType.split('/')[1];
        }
        if($.inArray(plainType.toLowerCase(), ['jpeg', 'jpg', 'png', 'pdf']) == -1) {
            alert('Only JPEG, JPG, PNG, PDF files are allowed');
            return false;
        }
        return true;
    }
	</script>
@endpush
