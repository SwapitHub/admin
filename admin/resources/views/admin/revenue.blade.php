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
    @endpush
@endsection
