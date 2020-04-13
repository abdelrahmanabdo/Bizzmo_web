<div class="col-sm-6 col-md-4 col-xs-12 card-container">
    <div class="bm-panel card">
        <div class="panel-main">
            <div class="panel-heading">{{ $heading }}</div>
            @foreach($contents as $content)
                @if($content['count'])
                <div class="panel-body text-center">
                    <span class="num-circle flex-container">
                        <span class="num">{{ $content['count'] }} </span>
                    </span>
                    <h4>{{ $content['text'] }}</h4>
                </div>
                @endif
            @endforeach
        </div>
        <div class="text-center panel-footer w-100">
            <a class="{{ $info ? 'bm-btn-muted' : 'bm-btn'}} w-100" href="{{ $link }}">View details</a>
        </div>
    </div>
</div>