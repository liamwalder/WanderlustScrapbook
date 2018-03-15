@extends('layouts.app')

@section('title', 'Create a trip')

@section('content')
    <div class="container" id="trip-create">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 content-block">
                    <h2 class="heading">Add a trip</h2>

                    @include('shared.messages')

                    <form method="post" action="{{ route('trip.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Trip Name</label>
                            <input class="form-control" name="name" />
                        </div>
                        <div class="form-group" id="save">
                            <button class="btn button-primary btn-block">Add Trip</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
