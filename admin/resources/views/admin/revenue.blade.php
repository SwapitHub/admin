@extends('layouts.layout')
@section('content')
    <style>
        .sell-graph canvas {
            height: 450px !important;
        }
    </style>
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="page-header-left">
                            <h3>revenue
                                <small>Diamond Admin Panel</small>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb pull-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('dashboard') }}">
                                    <i data-feather="home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">revenue</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container mt-5">
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Total Revenue (Daily)</h3>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Total Revenue (Weekly)</h3>
                </div>
                <div class="card-body">
                    <canvas id="weeklyRevenueChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Total Revenue (Monthly)</h3>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRevenueChart" width="400" height="200"></canvas>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <h3>Total Revenue (Yearly)</h3>
                </div>
                <div class="card-body">
                    <canvas id="yearlyRevenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var dailyLabels = {!! json_encode($dailyRevenue->keys()) !!};
                var dailyData = {!! json_encode($dailyRevenue->values()) !!};

                var weeklyLabels = {!! json_encode($weeklyRevenue->keys()) !!};
                var weeklyData = {!! json_encode($weeklyRevenue->values()) !!};

                var monthlyLabels = {!! json_encode(
                    $monthlyRevenue->keys()->map(function ($month) {
                        return date('F', mktime(0, 0, 0, $month, 1));
                    }),
                ) !!};
                var monthlyData = {!! json_encode($monthlyRevenue->values()) !!};

                var yearlyLabels = {!! json_encode($yearlyRevenue->keys()) !!};
                var yearlyData = {!! json_encode($yearlyRevenue->values()) !!};

                console.log("Daily Labels:", dailyLabels);
                console.log("Daily Data:", dailyData);
                console.log("Weekly Labels:", weeklyLabels);
                console.log("Weekly Data:", weeklyData);
                console.log("Monthly Labels:", monthlyLabels);
                console.log("Monthly Data:", monthlyData);
                console.log("Yearly Labels:", yearlyLabels);
                console.log("Yearly Data:", yearlyData);

                // Initialize Charts
                function initializeChart(ctx, labels, data, label) {
                    return new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                }

                var dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
                initializeChart(dailyCtx, dailyLabels, dailyData, 'Total Revenue ($)');

                var weeklyCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
                initializeChart(weeklyCtx, weeklyLabels, weeklyData, 'Total Revenue ($)');

                var monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
                initializeChart(monthlyCtx, monthlyLabels, monthlyData, 'Total Revenue ($)');

                var yearlyCtx = document.getElementById('yearlyRevenueChart').getContext('2d');
                initializeChart(yearlyCtx, yearlyLabels, yearlyData, 'Total Revenue ($)');
            });
        </script>
    @endpush
@endsection
