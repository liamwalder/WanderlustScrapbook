@extends('layouts.app')

@section('bodyClass', 'trip-single')
@section('navbarClass', 'container-fluid')

@section('content')
    <trip
        requested-trip-id="{{ $hash }}"
        :authenticated="{{ Auth::user() ? 1 : 0 }}"
    >
    </trip>
@endsection


