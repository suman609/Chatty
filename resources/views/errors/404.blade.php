@extends('templates.default')

@section ('content')
    <h3>Oops, that page could not be found</h3>
    <p><a href="{{ route ('home') }}">Go back to home page.</a></p>
@stop