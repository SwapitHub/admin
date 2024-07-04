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
    {{-- {!! $users->renderChartJsLibrary() !!}
    {!! $users->renderJs() !!}
    {!! $transaction->renderJs() !!}
    {!! $user_this_month->renderJs() !!}
    {!! $users_order->renderJs() !!} --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log("Daily Revenue Data:", {!! json_encode($dailyRevenue) !!});
            console.log("Weekly Revenue Data:", {!! json_encode($weeklyRevenue) !!});
            console.log("Monthly Revenue Data:", {!! json_encode($monthlyRevenue) !!});
            console.log("Yearly Revenue Data:", {!! json_encode($yearlyRevenue) !!});
            // Daily Revenue Chart
            var dailyCtx = document.getElementById('dailyRevenueChart').getContext('2d');
            var dailyRevenueChart = new Chart(dailyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($dailyRevenue->keys()) !!},
                    datasets: [{
                        label: 'Total Revenue ($)',
                        data: {!! json_encode($dailyRevenue->values()) !!},
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

            // Weekly Revenue Chart
            var weeklyCtx = document.getElementById('weeklyRevenueChart').getContext('2d');
            var weeklyRevenueChart = new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($weeklyRevenue->keys()) !!},
                    datasets: [{
                        label: 'Total Revenue ($)',
                        data: {!! json_encode($weeklyRevenue->values()) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
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

            // Monthly Revenue Chart
            var monthlyCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
            var monthlyRevenueChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($monthlyRevenue->keys()->map(function ($month) { return date('F', mktime(0, 0, 0, $month, 1)); })) !!},
                    datasets: [{
                        label: 'Total Revenue ($)',
                        data: {!! json_encode($monthlyRevenue->values()) !!},
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
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

            // Yearly Revenue Chart
            var yearlyCtx = document.getElementById('yearlyRevenueChart').getContext('2d');
            var yearlyRevenueChart = new Chart(yearlyCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($yearlyRevenue->keys()) !!},
                    datasets: [{
                        label: 'Total Revenue ($)',
                        data: {!! json_encode($yearlyRevenue->values()) !!},
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
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
        });
    </script>
    @endpush
@endsection
