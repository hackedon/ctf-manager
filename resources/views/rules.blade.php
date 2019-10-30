@extends('layouts.app')

@section('content')
    <div class="container">
        @if(auth()->check())
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background: #2d3238;">
                    @if(auth()->user()->isAdmin())
                        <li class="breadcrumb-item text-white"><a style="color: inherit" href="{{route('admin.home')}}">Admin Home</a></li>
                    @else
                        <li class="breadcrumb-item text-white"><a style="color: inherit" href="{{route('home')}}">Home</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">Rules</li>
                </ol>
            </nav>
        @endif

        <div class="row">
            <div class="col-md-10 text-white">
                <h2 class="display-4">Guidelines</h2>
                <ol>
                    <li>To start playing the CODEFEST CTF 2019 Access: <a style="color: inherit" href="https://hackmeifyoucan.hackedon.com">hackmeifyoucan.hackedon.com</a></li>
                    <li>Login using the registered credentials. (username, password)</li>
                    <li>You are free to choose any of the boxes given in any order.</li>
                    <li>Points allocated for each box is mentioned in the above site and the documentation is provided.</li>
                    <li>Progress of each box will be displayed.</li>
                    <li>Flags can be submitted via the above site irrespective of which box you’re attempting.</li>
                    <li>Hints can be obtained to bypass a level (Subject to points deduction).</li>
                    <li>If you require hints for the 1st level, please inform the organizers. They will grant a variable amount of loan points based on the box and level.</li>
                    <li>Loan points will be deducted at the final marks evaluation.</li>
                    <li>Flag formats vary in each box.</li>
                    <li>The flag submission endpoint is rate-limited. Do not attempt to submit more than 3 flags per minute, you’ll be locked out.</li>
                    <li>Remote Connection/Desktop applications (e.g: TeamViewer, AnyDesk etc.) are completely prohibited from use and will lead to your disqualification</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 text-white">
                <h2 class="display-4">Rules</h2>
                <ol>
                    <li>
                        A ‘Final Report’ (A word document: doc, docx) should be submitted containing the following:
                        <ul>
                            <li>CTF Box Attempted</li>
                            <li>Flags Obtained (screenshots, flag location etc.)</li>
                            <li>A small description on how you obtained the flag and tools & techniques used to solve.</li>
                            <li>File Name: ‘TeamName.docx’</li>
                        </ul>
                    </li>
                    <li>You should upload your report to <a style="color: inherit" href="https://hackmeifyoucan.hackedon.com">hackmeifyoucan.hackedon.com</a> before the clock expires. (Please note that the upload endpoint will not accept any files after the clock
                        expires.)
                    </li>
                    <li>Each team has a maximum of 5 report uploads.</li>
                    <li>Team name (Display Name) cannot be changed (Same name as the initial registration).</li>
                    <li>Marks will be deducted for hints.</li>
                    <li>Bypassing the box in any way is prohibited and that will lead the team to be disqualified.</li>
                    <li>Creating any unwanted traffic to the flag submission systems is strictly prohibited.</li>
                    <li>All the flags are located in the box provided, therefore flag submission system is out of bounds.</li>
                    <li>The marks displayed on the system is subject to change.</li>
                    <li>Changes may occur after judges’ evaluation of Flag Report submitted by your team.</li>
                    <li>Finalists will be notified within the day & are expected to participate in the final awards ceremony.</li>
{{--                    <li>Awards Ceremony will start at 2.00pm on 3rd October 2018.</li>--}}
                </ol>
            </div>
        </div>

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
