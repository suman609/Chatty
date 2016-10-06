@extends('templates.default')

@section('content')
    <!-- query is coming from navigation blade for [name="query"] -->
    <h3>Your search for "{{ Request::input('query') }}"</h3>

    @if (!$users->count())
        <p>No results found.</p>
    @else
    <div class="row">
        <div class="col-md-12">

            @foreach ($users as $user)
                @include('users/partials/userblock')
            @endforeach

        </div>
    </div>
    @endif
@stop