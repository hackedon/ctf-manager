@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: #2d3238;">
                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Admin Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Boxes</li>
                <li class="breadcrumb-item active" aria-current="page">{{$box->title}}</li>
            </ol>
        </nav>
        <div class="row justify-content-between">
            <div class="col-md-8">
                <img src="/storage/boxes/{{$box->logo}}" class="float-left img-thumbnail mr-3" style="width: 250px">
                <div class="text-white ml-3">
                    <h1 class="display-3">{{$box->title}}</h1>
                    <p>
                        {{$box->description}}
                        @if(isset($box->author)) <br><small class="text-muted">{{'@'.$box->author}}</small> @endif @if(isset($box->difficulty)) | <small
                            class="text-muted">difficulty: {{$box->difficulty}}/10</small> @endif
                    </p>
                </div>
                <div>
                    <button class="btn btn-outline-light" data-toggle="modal" data-target="#storeFlagModal">Add Flag</button>
                    <button class="btn btn-outline-danger" onclick="if(confirm('Are you absolutely sure?')) deleteBox()">Delete Box</button>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card bg-dark shadow">
                    <div class="card-body">
                        <h1 class="display-4 text-center">Team Progress</h1>
                        <table class="table table-dark text-center table-sm table-striped">
                            <thead>
                            <tr>
                                <th>Team</th>
                                <th>Progress</th>
                                <th>Score</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($boxProgress as $row)
                                <tr>
                                    <td>{{$row['team']}}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: {{$row['progress']}}%"
                                                 aria-valuenow="{{$row['progress']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                    <td>{{$row['points']}}</td>
                                    <td><a href="{{route('admin.show.team',['id'=>$row['team_id']])}}" class="btn-link" style="color: #2e8592"><i data-feather="eye"></i></a></td>
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
                        <div class="d-flex justify-content-center mb-2">
                            <h1 class="mr-5">Flags </h1>
                            <button class="btn btn-outline-danger btn-sm" type="button" data-toggle="collapse" data-target="#flagsCollapse" aria-expanded="false" aria-controls="flagsCollapse">
                                <i data-feather="eye"></i><i data-feather="alert-triangle"></i>
                            </button>
                        </div>

                        <div class="collapse" id="flagsCollapse">
                            <table class="table table-dark text-center table-sm table-striped">
                                <thead>
                                <tr>
                                    <th>Flag No.</th>
                                    <th>Flag</th>
                                    <th>Points</th>
                                    <th>Submissions</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($flags as $flag)
                                    <tr>
                                        <td>{{$flag->flag_no}}</td>
                                        <td>{{$flag->flag}}</td>
                                        <td>{{$flag->points}}</td>
                                        <td>{{$flag->submissions->count()}}</td>
                                        <td><a href="#" onclick="if(confirm('Are you sure?')) deleteFlag({{$flag->id}})" class="btn-link" style="color: #920000"><i data-feather="x-circle"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="storeFlagModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Flag</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.store.flag')}}" method="POST">
                    @csrf
                    <input type="hidden" name="box_id" value="{{$box->id}}">
                    <div class="modal-body">
                        <div class="form-group">
                            <input class="form-control" type="text" name="flag" required placeholder="Flag">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" step="1" name="points" required placeholder="Points">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function deleteFlag(level_id) {
            axios.post('{{route('admin.delete.flag')}}', {
                level_id: level_id
            }).then(resp => {
                window.location.reload();
            }).catch(err => {
                toastr.error('Error');
                console.log(err);
            })
        }

        function deleteBox() {
            axios.delete('{{route('admin.delete.box',['id'=>$box->id])}}')
                .then(() => {
                    window.location = '{{route('admin.home')}}';
                }).catch(err => {
                toastr.error('Error');
                console.log(err);
            })
        }
    </script>


@endsection
