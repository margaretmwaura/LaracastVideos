<reply  :attributes="{{ $reply }}" inline-template v-cloak>
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
                    <favorite :reply="{{ $reply }}"></favorite>
{{--                    {{ $reply->favorites()->count() }}--}}
{{--                    <form method="POST" action="/replies/{{ $reply->id }}/favorites">--}}
{{--                        {{ csrf_field() }}--}}

{{--                    </form>--}}
                </div>
            </div>

        </div>


        <div class="panel-body">
            <div v-if="editting">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                <button type="submit" class="btn btn-xs btn-primary" @click="update"> Update </button>
                <button type="submit" class="btn btn-xs btn-link" @click="editting=false"> Cancel </button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>
        @can('update',$reply)
            <div class="panel-footer level">
                <button type="submit" class="btn btn-xs mr-1" @click="editting=true">Edit</button>
                <button type="submit" class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>

            </div>
        @endcan
    </div>
</reply>

