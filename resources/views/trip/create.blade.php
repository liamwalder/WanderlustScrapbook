@extends('layouts.app')

@section('content')
    <div class="container" id="trip-create">
        <div class="row">
            <div class="col-md-8 offset-md-3">
                <div class="col-md-12 content-block">
                    <a href="{{ route('trip.index') }}" id="back-to-trips">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to trips
                    </a>
                    <h1 class="heading">Add a trip</h1>
                    @include('shared.errors')
                    <form method="post" action="{{ route('trip.store') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Trip Name</label>
                            <input class="form-control" name="name" />
                        </div>
                        <div class="form-group" id="save">
                            <button class="btn button-primary btn-block">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
