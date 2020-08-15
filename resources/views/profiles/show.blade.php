@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="page-header">
                    <h1>
                        {{ $profileUser->name }}
                    </h1>
                    <small>
                        Since {{  $profileUser->created_at->diffForHumans()}}
                    </small>
                    @can('update',$profileUser)
                       <form method="POST" action="/api/users/{{ $profileUser->id }}/avatar" enctype="multipart/form-data">

                           {{ csrf_field() }}
                        <input type="file" name="avatar">

                           <button type="submit" class="btn btn-primary">Add Avatar</button>
                       </form>
                    @endcan

                    <img src="{{ asset($profileUser->avatar_path) }}" width="200" height="200">
                </div>


                @forelse($activities as $date =>$activity)

                    <h3 class="page-header">{{ $date }}</h3>
                    @foreach($activity as $record)
                        @if (view()->exists("profiles.activities.{$record->type}"))
                            @include ("profiles.activities.{$record->type}", ['activity' => $record])
                        @endif
                    @endforeach
                @empty
                    <p>There is no activity for this users</p>
                @endforelse
            </div>
        </div>

    </div>



@endsection
