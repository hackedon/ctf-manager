@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center" style="margin-top: 150px">
            <div>
                <div class="countdown" data-date="31-10-2019" data-time="17:10">
                    <div class="day d-none"><span class="num"></span><span class="word"></span></div>
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


