@extends('layouts.app')

@section('content')
    <div class="container" style="margin-top: -30px">
        <div class="row justify-content-center">
            <div class="col-md-4 d-flex justify-content-center">
                <img src="{{asset('img/logo.png')}}" style="width: 80%">
            </div>
        </div>

        @if($allowFlagSubmission)
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
        @endif

        @if($allowReportUploads)
            <div class="row justify-content-center mb-5">
                <div class="col-md-8">
                    <div class="card text-white shadow" style="background: #1d2124">
                        <div class="card-body text-center">
                            <form method="POST" action="{{route('user.upload.report')}}" enctype="multipart/form-data">
                                @csrf
                                <div class="row justify-content-center text-center text-muted">
                                    <div class="form-group col-md-8">
                                        <h3>CTF Report</h3>
                                        <input type="file" class="form-control-file" required name="report" {{$canUpload ? '':'disabled'}}>
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <button type="submit" class="btn btn-outline-info" style="width: 62%" {{$canUpload ? '':'disabled'}}>Upload</button>
                                </div>
                                <small class="text-muted">Only .docx and .doc allowed</small> <br>
                                <small class="text-muted">Uploads Remaining: {{$remainingUploads}}</small>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h4 class="display-4">Boxes</h4>
            </div>
        </div>
        <div class="row justify-content-center">
            @foreach($summary as $row)
                <div class="col-sm-4">
                    <div class="card text-white ml-2 shadow-sm h-100" style="background: #1b1e21; margin-top: 10px">
                        <img class="card-img-top" src="/storage/boxes/{{$row['box']->logo}}" alt="Card image cap" style="height: 200px">
                        <div class="card-body">
                            <h5 class="card-title">{{$row['box']->title}}</h5>
                            <p class="card-text">{{$row['box']->description}}</p>
                        </div>
                        <div class="card-footer text-center">
                            @if(isset($row['box']->url))
                                <small><a class="text-muted" style="color: inherit" target="_blank" href="{{$row['box']->url}}">{{$row['box']->url}}</a></small> <br>
                            @endif
                            @if(isset($row['box']->difficulty))
                                <small class="text-muted">difficulty</small>
                                <div class="progress mb-3" style="height: 5px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{$row['box']->difficulty / 10 * 100}}%;"
                                         aria-valuenow="{{$row['box']->difficulty / 10 * 100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            @endif
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar progress-bar-striped {{$row['completePercentage'] === 100 ? 'bg-success' : 'progress-bar-animated bg-dark'}}" role="progressbar"
                                     style="width: {{$row['completePercentage']}}%;"
                                     aria-valuenow="{{$row['completePercentage']}}" aria-valuemin="0" aria-valuemax="100">
                                    <strong>{{$row['completePercentage']}}%</strong>
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                {{$row['flagsFoundText']}}
                                <h4><span class="badge badge-info">{{$row['points'].' / '.$row['totalPoints']}} Points</span></h4>
                                <button style="color: inherit" class="btn btn-link btn-sm text-muted" onclick="if(confirm('Are you sure? (Subject to points deduction)')) requestHint('{{$row['box']->id}}')">
                                    Request Hint
                                </button>
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
                                <span><i
                                        data-feather="rss"></i> Flag no. {{$submission->level->flag_no}} was submitted by {{auth()->user()->display_name}} for {{$submission->level->points}} points.<span
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
    <script type="text/javascript">

        const requestHint = box_id => {
            axios.post('{{route('user.request.hint')}}', {box_id}).then(res => {
                toastr.success('Request for hint has been recorded');
            }).catch(e => {
                toastr.error(e.response.data.message);
                toastr.info('You can request for hints once every 10 minutes.');
            });
        };

    </script>
@endsection
