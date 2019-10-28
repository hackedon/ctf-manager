@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card-columns">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Boxes</h5>
                            <h1 class="text-danger text-center">{{$counts['boxes']}}</h1>
                            <ul class="list-group list-group-flush">
                                @foreach($boxes as $box)
                                    <li class="list-group-item"><a href="{{route('admin.show.box',['id'=>$box->id])}}">{{$box->title}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addBoxModal">Add Box</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Teams</h5>
                            <h1 class="text-danger text-center">{{$counts['teams']}}</h1>
                            <ul class="list-group list-group-flush">
                                @foreach($teams as $team)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{route('admin.show.team',['id'=> $team->id])}}">{{$team->display_name}}</a>
                                        <span class="badge badge-primary badge-pill">{{$team->submissions->count()}}</span></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#addTeamModal">Register Team</button>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="text-center">Total Flags</h5>
                            <h1 class="text-danger text-center">{{$counts['flags']}}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addBoxModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Box</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <input class="form-control" type="number" step="1" name="difficulty" placeholder="Difficulty">
                        </div>
                        <div class="form-group">
                            <label>Box Logo</label>
                            <input class="form-control-file" type="file" name="logo" placeholder="Logo">
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Register Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                            <input class="form-control" type="text" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
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
