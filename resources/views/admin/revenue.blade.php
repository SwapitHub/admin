@extends('layouts.layout')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                            <h3>Revenue
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
                            <li class="breadcrumb-item active">Reports</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container">
            <div class="row">
                <div class="col-lg-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $dailyRevenue->options['chart_title'] }}</h5>
                        </div>
                        <div class="card-body expense-chart">
                            {!! $dailyRevenue->renderHtml() !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $weekleyRevenue->options['chart_title'] }}</h5>
                        </div>
                        <div class="card-body expense-chart">
                            {!! $weekleyRevenue->renderHtml() !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $monthlyRevenue->options['chart_title'] }}</h5>
                        </div>
                        <div class="card-body expense-chart">
                            {!! $monthlyRevenue->renderHtml() !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $yearlyRevenue->options['chart_title'] }}</h5>
                        </div>
                        <div class="card-body expense-chart">
                            {!! $yearlyRevenue->renderHtml() !!}
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>NetRevenue</h5>
                        </div>
                        <div class="card-body expense-chart">
                            <canvas id="netRevenueChart"></canvas>
                        </div>
                    </div>
                </div>


                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>Average order value ($)</h5>
                        </div>
                        <div class="card-body expense-chart">
                               <canvas id="aovChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mx-auto">
                    <div class="card">
                        <div class="card-header">
                            <h5>Average basket (U).</h5>
                        </div>
                        <div class="card-body expense-chart">
                               <canvas id="aovbasketChart"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

    @push('scripts')
    {!! $dailyRevenue->renderChartJsLibrary() !!}
    {!! $dailyRevenue->renderJs() !!}
    {!! $weekleyRevenue->renderJs() !!}
    {!! $monthlyRevenue->renderJs() !!}
    {!! $yearlyRevenue->renderJs() !!}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('aovChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($averageOrderValues->pluck('year')) !!},
                    datasets: [{
                        label: 'Average Order Value ($)',
                        data: {!! json_encode($averageOrderValues->pluck('average_order_value')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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

        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('aovbasketChart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($averageBasketValues->pluck('year')) !!},
                    datasets: [{
                        label: 'Average Basket Value (U)',
                        data: {!! json_encode($averageBasketValues->pluck('average_basket_value')) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
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

        var ctx = document.getElementById('netRevenueChart').getContext('2d');
        var chartData = @json($netRevenue);

        var labels = chartData.map(data => data.year);
        var netRevenue = chartData.map(data => data.net_revenue);

        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Net Revenue',
                    data: netRevenue,
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
    </script>
    @endpush
@endsection
