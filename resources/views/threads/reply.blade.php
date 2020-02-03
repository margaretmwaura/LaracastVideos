<div class="panel panel-default" id="reply-{{ $reply->id }}">
    <div class="panel-heading">
        <div class="level">
            <h5 class="flex">
                <a href="/profiles/{{ $reply->owner->name }}" >
                    {{$reply->owner->name}}
                </a>
                said
                {{ $reply->created_at->diffForHumans()}} ...
            </h5>


            <div>
                {{ $reply->favorites()->count() }}
                <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : ' ' }}>
                        {{ $reply->favorites_count }} {{ str_plural('Favorite',  $reply->favorites_count) }}
                        </button>
                </form>
            </div>
        </div>

    </div>


    <div class="panel-body"> {{ $reply->body }}</div>
    @can('update',$reply)
    <div class="panel-footer">

            <form action="/replies/{{ $reply->id }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Delete </button>
            </form>

    </div>
    @endcan
</div>
