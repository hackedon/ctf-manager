@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: #2d3238;">
                <li class="breadcrumb-item active">Admin Home</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-columns">
                    <div class="card shadow text-white" style="background: #1d2124">
                        <div class="card-body">
                            <h4 class="text-center">Boxes</h4>
                            <h1 class="text-danger text-center">{{$counts['boxes']}}</h1>
                            <ul class="list-group list-group-flush">
                                @foreach($boxes as $box)
                                    <li class="list-group-item" style="background: #1d2124"><a style="color: inherit" href="{{route('admin.show.box',['id'=>$box->id])}}">{{$box->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-light btn-block" data-toggle="modal" data-target="#addBoxModal">Add Box</button>
                        </div>
                    </div>

                    <div class="card shadow text-white" style="background: #1d2124">
                        <div class="card-body">
                            <h4 class="text-center">Teams</h4>
                            <h1 class="text-danger text-center">{{$counts['teams']}}</h1>
                            <ul class="list-group list-group-flush">
                                @foreach($teams as $team)
                                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background: #1d2124">
                                        <a style="color: inherit" href="{{route('admin.show.team',['id'=> $team->id])}}">{{$team->display_name}}</a>
                                        <span class="badge badge-warning badge-pill">{{$team->submissions->count()}}</span></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-light btn-block" data-toggle="modal" data-target="#addTeamModal">Register Team</button>
                        </div>
                    </div>

                    <div class="card shadow text-white" style="background: #1d2124">
                        <div class="card-body">
                            <h4 class="text-center">Total Flags</h4>
                            <h1 class="text-danger text-center">{{$counts['flags']}}</h1>
                        </div>
                    </div>

                    <div class="card shadow text-white" style="background: #1d2124">
                        <div class="card-body text-center">
                            <h4 class="text-center">Global Settings</h4>
                            <form method="POST" action="{{route('admin.save.settings')}}">
                                @csrf
                                <div class="form-group">
                                    <label>Allow Flag Submission</label>
                                    <div class="form-check">
                                        <input onchange="this.form.submit()" class="form-check-input" type="radio" name="allowFlagSubmission" value="1" {{$allowFlagSubmission ? 'checked' : ''}}>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input onchange="this.form.submit()" class="form-check-input" type="radio" name="allowFlagSubmission" value="0" {{$allowFlagSubmission ? '' : 'checked'}}>
                                        <label class="form-check-label" for="exampleRadios2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form method="POST" action="{{route('admin.save.settings')}}">
                                @csrf
                                <div class="form-group">
                                    <label>Allow Report Upload</label>
                                    <div class="form-check">
                                        <input onchange="this.form.submit()" class="form-check-input" type="radio" name="allowReportUploads" value="1" {{$allowReportUploads ? 'checked' : ''}}>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input onchange="this.form.submit()" class="form-check-input" type="radio" name="allowReportUploads" value="0" {{$allowReportUploads ? '' : 'checked'}}>
                                        <label class="form-check-label" for="exampleRadios2">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </form>
                        </div>
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


    <div class="modal fade" id="addBoxModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white" style="background: #1d2124">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Box</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.store.box')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="title" placeholder="Box Name" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="description" placeholder="Description" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="number" step="1" name="difficulty" placeholder="Difficulty" required>
                        </div>
                        <div class="form-group">
                            <label>Box Logo</label>
                            <input class="form-control-file" type="file" name="logo" placeholder="Logo" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="author" placeholder="Author">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="url" placeholder="URL">
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

    <div class="modal fade" id="addTeamModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content text-white" style="background: #1d2124">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register Team</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.store.team')}}" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" type="text" name="display_name" placeholder="Display Name" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="affiliation" placeholder="Affiliation (i.e: university)">
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control mb-1" type="password" name="password" placeholder="Password" required>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm Password" required>
                        </div>
                        <div class="form-group">
                            <label>Team Logo</label>
                            <input class="form-control-file" type="file" name="avatar" placeholder="Avatar">
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
@endsection
