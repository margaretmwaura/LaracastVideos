@component('profiles.activities.activity')
    @slot('heading')
        <a href="{{ $activity->subject->path() }}"> {{ $profileUser->name }} published a thread {{ $activity->subject->title }}</a>
    @endslot

    @slot('body')
        {{ $activity->subject->body }}
    @endslot
@endcomponent



