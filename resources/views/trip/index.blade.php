@extends('layouts.app')

@section('content')
    <div class="container" id="trip-index">
        <div class="row">
            <div class="col-md-8 offset-md-3">
                <div class="col-md-12 content-block">
                    <h1 class="heading">Your Trips</h1>
                    <div class="trips">
                        @if ($trips->count() !== 0)
                            @foreach($trips as $trip)
                                <div class="trip">
                                    <div class="name">
                                        <a href="{{ route('trip', ['id' => $trip->id]) }}">{{ $trip->name }}</a>
                                    </div>
                                    <div class="details">
                                        <span class="countries">
                                            {{ $trip->countries }} countries
                                        </span>
                                        <span class="circle-separator"></span>
                                        <span class="miles">
                                            {{ $trip->miles }} miles
                                        </span>
                                        <span class="circle-separator"></span>
                                        <span class="locations">
                                            {{ $trip->locations->count() }} locations
                                        </span>
                                        <span class="circle-separator"></span>
                                        <form class="delete-trip" method="POST" action="{{ route('trip.delete', ['id' => $trip->id]) }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <button class="text-danger delete-trip">Delete Trip</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="notice">You don't have any trips. Add your first by clicking below</p>
                        @endif

                        <div>
                            <a class="btn button-primary btn-block" href="{{ route('trip.create') }}" id="add-trip">Add a trip</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-scripts')
    <script>
        $(".delete-trip").on("submit", function(){
            return confirm("Do you want to delete this item?");
        });
    </script>
@endsection
