@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-group">
                    @foreach($boxes as $box)
                        <div class="card text-white bg-dark ml-2 shadow">
                            <img class="card-img-top" src="/storage/boxes/{{$box->logo}}" alt="Card image cap">
                            <div class="card-body">
                                <h5 class="card-title">{{$box->title}}</h5>
                                <p class="card-text">{{$box->description}}</p>
                                <p class="card-text"><small class="text-muted">{{$box->author}}</small></p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
