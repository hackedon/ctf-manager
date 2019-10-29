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
                    <li class="breadcrumb-item active" aria-current="page">Countdown</li>
                </ol>
            </nav>
        @endif

        <div class="row justify-content-center" style="margin-top: 150px">
            <div>
                <div class="countdown" data-date="31-10-2019" data-time="17:10">
                    <div class="day"><span class="num"></span><span class="word"></span></div>
                    <div class="hour"><span class="num"></span><span class="word"></span></div>
                    <div class="min"><span class="num"></span><span class="word"></span></div>
                    <div class="sec"><span class="num"></span><span class="word"></span></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const efcc_countdown = new countdown({
            target: '.countdown',
            dayWord: ' days',
            hourWord: ' hours',
            minWord: ' mins',
            secWord: ' secs'
        });
    </script>
@endsection


