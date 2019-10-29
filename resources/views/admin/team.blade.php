@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: #2d3238;">
                <li class="breadcrumb-item text-white"><a style="color: inherit" href="{{route('admin.home')}}">Admin Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Teams</li>
                <li class="breadcrumb-item active" aria-current="page">{{$team->display_name}}</li>
            </ol>
        </nav>
        <div class="row justify-content-between">
            <div class="col-md-8">
                @if(isset($team->avatar))
                    <img src="/storage/avatars/{{$team->avatar}}" class="float-left img-thumbnail mr-3" style="width: 150px">
                @endif
                <div class="text-white {{isset($team->avatar) ? 'ml-3': ''}}">
                    <h1 class="display-3">{{$team->display_name}}</h1>
                </div>
                <div>
                    <button class="btn btn-outline-danger" onclick="if(confirm('Are you absolutely sure?')) deleteTeam()">Delete Team</button>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <h1 class="display-4 text-center">Box Progress</h1>
                        <table class="table table-dark text-center table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Box</th>
                                <th>Progress</th>
                                <th>Score</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($progress as $row)
                                <tr>
                                    <td>{{$row['box']}}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped {{$row['progress'] === 100 ? 'bg-success' : 'progress-bar-animated bg-danger'}}" role="progressbar"
                                                 style="width: {{$row['progress']}}%"
                                                 aria-valuenow="{{$row['progress']}}" aria-valuemin="0" aria-valuemax="100">{{$row['flagFraction']}}</div>
                                        </div>
                                    </td>
                                    <td>{{$row['score']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <h1 class="display-4 text-center">Hint Requests</h1>
                        <table class="table table-dark table-sm table-borderless ">
                            <thead>
                            <tr class="text-center">
                                <th>ID</th>
                                <th>Team</th>
                                <th>Box</th>
                                <th>Cost</th>
                                <th>Resolved</th>
                                <th>Last Updated</th>
                                <th>Mark as</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($hintRequests->count() > 0)
                                @foreach($hintRequests as $request)
                                    <tr class="text-center" style="background: {{$request->active ? '#421010':'#005f28'}}">
                                        <td>{{$request->id}}</td>
                                        <td>{{$request->user->username}}</td>
                                        <td>{{$request->box->title}}</td>
                                        <td>
                                            <input type="number" min="0" max="10" id="cost_input_{{$request->id}}" value="{{$request->cost}}" {{$request->active ? 'disabled':''}}>
                                            <button class="btn btn-sm"
                                                    {{$request->active ? 'disabled':''}} onclick="if(confirm('Are you sure?')) updateCost('{{$request->id}}', 'cost_input_{{$request->id}}')">Update
                                            </button>
                                        </td>
                                        <td><span class="badge {{$request->active ? 'badge-danger':'badge-success'}}">{{$request->active ? 'UNRESOLVED':'RESOLVED'}}</span></td>
                                        <td>{{$request->updated_at->diffForHumans()}}</td>
                                        <td>
                                            <div class="form-group">
                                                <select onchange="if(confirm('Are you sure?')) toggleActiveStatus('{{$request->id}}',this.value)">
                                                    <option value="1" {{$request->active ? '':'selected'}}>Resolved</option>
                                                    <option value="2" {{$request->active ? 'selected':''}}>Unresolved</option>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">Nothing here yet!</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <h1 class="display-4 text-center">Report Uploads</h1>
                        <table class="table table-dark text-center table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Original Filename</th>
                                <th>Created</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($reports->count() === 0)
                                <tr>
                                    <td colspan="3" class="text-center">Nothing here yet!</td>
                                </tr>
                            @endif
                            @foreach($reports as $report)
                                <tr>
                                    <td>{{$report->original_filename}}</td>
                                    <td>{{$report->created_at}} | <small>{{$report->created_at->diffForHumans()}}</small></td>
                                    <td>
                                        <a style="color: inherit" href="/storage/reports/{{$report->submission}}">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <h1 class="display-4 text-center">Feed</h1>
                        <table class="table table-dark table-sm table-borderless table-striped">
                            <tbody>
                            @foreach($feed as $submission)
                                <tr>
                                    <td class="d-flex justify-content-between">
                                        <span><i
                                                data-feather="rss"></i> Flag no. {{$submission->level->flag_no}} was submitted by {{$team->display_name}} for {{$submission->level->points}} points. <span
                                                class="text-muted">{{$submission->created_at->diffForHumans()}}</span></span>
                                        <h4><span class="badge badge-danger">{{$submission->level->box->title}}</span></h4>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5 mb-3">
            <div class="col-md-12 text-center text-muted">
                &copy;{{date('Y')}} <a href="https://hackedon.com" target="_blank" style="color: inherit" rel="nofollow">HackedON</a>
            </div>
        </div>
    </div>

    <script>
        function deleteTeam() {
            axios.delete('{{route('admin.delete.team',['id'=>$team->id])}}')
                .then(() => {
                    window.location = '{{route('admin.home')}}';
                }).catch(err => {
                toastr.error('Error');
                console.log(err);
            })
        }

        const toggleActiveStatus = (request_id, value) => {
            axios.post('{{route('admin.toggle.active')}}', {
                request_id,
                value
            }).then(res => {
                window.location.reload();
            }).catch(e => {
                toastr.error('Error');
            })
        };

        const updateCost = (request_id, input_id) => {
            let cost = document.getElementById(input_id).value;
            axios.post('{{route('admin.update.cost')}}', {
                request_id,
                cost
            }).then(res => {
                toastr.success(`Cost updated to ${cost} points`);
            }).catch(e => {
                toastr.error('Error');
            });
        };
    </script>
@endsection
