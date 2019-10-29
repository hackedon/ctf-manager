@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: #2d3238;">
                <li class="breadcrumb-item text-white"><a style="color: inherit" href="{{route('admin.home')}}">Admin Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hint Requests</li>
            </ol>
        </nav>

        <div class="row justify-content-between">
            <div class="col-md-12">

                <table class="table table-dark table-sm table-borderless">
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
                            <tr class=" text-center" style="background: {{$request->active ? '#421010':'#005f28'}}">
                                <td>{{$request->id}}</td>
                                <td><a style="color: inherit" href="{{route('admin.show.team',['id'=>$request->user->id])}}">{{$request->user->username}}</a></td>
                                <td><a style="color: inherit" href="{{route('admin.show.box',['id'=>$request->box->id])}}">{{$request->box->title}}</a></td>
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
                        <tr>
                            <td colspan="7">
                                <div class="row justify-content-center">
                                    <div>
                                        {{$hintRequests->links()}}
                                    </div>
                                </div>
                            </td>
                        </tr>
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

    <script>
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
