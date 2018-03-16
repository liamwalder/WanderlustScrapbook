@extends('layouts.app')

@section('title', 'Trips')

@section('content')
    <div class="container" id="trip-index">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-12 content-block">
                    <h3 class="heading">Your Trips</h3>
                    <div class="trips">
                        @if ($trips->count() !== 0)
                            @foreach($trips as $trip)
                                <div class="trip">
                                    <div class="name">
                                        <a href="{{ route('trip', ['hash' => $trip->hash]) }}">{{ $trip->name }}</a>
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
                            <p class="notice">You don't have any trips. Add your first by clicking above.</p>
                        @endif
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
