@extends('layouts.app')

@section('content')
{{ Form::open(array('id' => 'frmManage', 'files' => true)) }}
	<div class="container">
		Hello
	</div>
</form>
@endsection

@push('scripts')	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.1.1/socket.io.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			Echo.channel('test-event').listen('ExampleEvent', function(e) {
				console.log('zz');
                console.log(e);
            });
		});
	</script>
@endpush
