@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 d-flex justify-content-center">
                <img src="{{asset('img/logo.png')}}" style="width: 80%">
            </div>
        </div>

        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <div class="card text-white shadow" style="background: #1d2124">
                    <div class="card-body">
                        <form method="POST" action="{{route('user.submit.flag')}}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="form-group col-md-8">
                                    <input type="text" class="form-control" required placeholder="Enter Flag.." name="flag" autofocus>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <button type="submit" class="btn btn-outline-danger" style="width: 62%">Submit Flag</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h4 class="display-4">Boxes</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($summary as $row)
                <div class="col-sm-4">
                    <div class="card text-white ml-2 shadow-sm h-100" style="background: #1b1e21">
                        <img class="card-img-top" src="/storage/boxes/{{$row['box']->logo}}" alt="Card image cap" style="height: 200px">
                        <div class="card-body">
                            <h5 class="card-title">{{$row['box']->title}}</h5>
                            <p class="card-text">{{$row['box']->description}}</p>
                        </div>
                        <div class="card-footer">
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar {{$row['completePercentage'] === 100 ? 'bg-success' : 'progress-bar-striped progress-bar-animated bg-dark'}}" role="progressbar" style="width: {{$row['completePercentage']}}%;"
                                     aria-valuenow="{{$row['completePercentage']}}" aria-valuemin="0" aria-valuemax="100">
                                    <strong>{{$row['completePercentage']}}%</strong>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{$row['flagsFoundText']}}
                                <h4><span class="badge badge-info">{{$row['points']}} Points</span></h4>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-8 text-center">
                <h4 class="display-4">Feed</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-dark table-sm table-borderless table-striped">
                    <tbody>
                    @foreach($feed as $submission)
                        <tr>
                            <td class="d-flex justify-content-between">
                                <span><i data-feather="rss"></i> Flag no. {{$submission->level->flag_no}} was submitted by {{auth()->user()->display_name}} for {{$submission->level->points}} points.<span
                                        class="text-muted"> &nbsp; {{$submission->created_at->diffForHumans()}}</span></span>
                                <h5><span class="badge badge-danger">{{$submission->box->title}}</span></h5>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--        //// FOOTER ////        --}}
        <div class="row mt-5 mb-3">
            <div class="col-md-12 d-flex justify-content-center">
                <img src="{{asset('img/logos.png')}}" style="width: 60%">
            </div>
            <div class="col-md-12 text-center text-muted">
                &copy;{{date('Y')}} <a href="https://hackedon.com" target="_blank" style="color: inherit" rel="nofollow">HackedON</a>
            </div>
        </div>
    </div>
@endsection
