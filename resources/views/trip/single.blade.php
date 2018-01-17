@extends('layouts.app')

@section('bodyClass', 'trip-single')
@section('navbarClass', 'container-fluid')

@section('content')
    <trip :requested-trip-id="{{ $id }}"></trip>
@endsection


