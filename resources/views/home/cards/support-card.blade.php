<div class="panel-main">
    <div class="card-main">
        <div class="upper-meta">
            <div class="title-sm">Issue #{{ $support->id }}</div>
            <div>
                <div class="tag">open issue</div>
            </div>
        </div>
        <div class="title-lg red">{{ $support->name }}</div>
        <div class="subtitle text-truncate">{{ $support->message }}</div>
    </div>
    <div class="card-footer">
        <span class="date">{{ $show_date }} ago</span>
    </div>
</div>
<div class="text-center panel-footer w-100">
    <a class="bm-btn w-100" href="/supports/view/{{$support->id}}">View details</a>
</div>