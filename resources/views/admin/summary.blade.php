@extends('layouts.app')
@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background: #2d3238;">
                <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Admin Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Summary</li>
            </ol>
        </nav>
        <div class="row justify-content-between">
            <div class="col-md-12">
                <canvas id="canvas"></canvas>
            </div>
        </div>
    </div>
    <script>

        setInterval(() => {
            window.location.reload();
        }, 60000);

        let chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)',
            customColor: 'rgb(48,48,48)',
        };
        let barChartData = {
            labels: [
                @foreach($points as $row)
                {!! '"'.$row['team'].'",' !!}
                @endforeach
            ],
            datasets: [{
                label: 'Combined Score',
                backgroundColor: chartColors.customColor,
                borderColor: chartColors.red,
                borderWidth: 1,
                data: [
                    @foreach($points as $row)
                    {!! $row['points'].',' !!}
                    @endforeach
                ],
            }]

        };

        window.onload = function () {
            var ctx = document.getElementById('canvas').getContext('2d');
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'CTF Summary'
                    }
                }
            });
        }


    </script>
@endsection
