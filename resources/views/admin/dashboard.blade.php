@extends('layouts.layout')
@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="page-header-left">
                            <h3>Dashboard
                                <small>Dimond Admin panel</small>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ol class="breadcrumb pull-right">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i data-feather="home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-3 col-md-6 xl-50">
                    {{-- <a href="{{ route('admin.widget.list') }}"> --}}
                    <div class="card o-hidden widget-cards">
                        <div class="warning-box card-body">
                            <div class="media static-top-widget align-items-center">
                                <div class="icons-widgets">
                                    <div class="align-self-center text-center">
                                        <i data-feather="navigation" class="font-warning"></i>
                                    </div>
                                </div>
                                <div class="media-body media-doller">
                                    <span class="m-0">Daily sales</span>
                                    <h3 class="mb-0"> <span class="counter">$ {{ $dalysales }}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </a> --}}
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    {{-- <a href="{{ route('admin.widget.list') }}"> --}}
                    <div class="card o-hidden widget-cards">
                        <div class="secondary-box card-body">
                            <div class="media static-top-widget align-items-center">
                                <div class="icons-widgets">
                                    <div class="align-self-center text-center">
                                        <i data-feather="navigation" class="font-warning"></i>
                                    </div>
                                </div>
                                <div class="media-body media-doller">
                                    <span class="m-0">Monthly sales </span>
                                    <h3 class="mb-0"> <span class="counter">$ {{ $monthlysales }}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </a> --}}
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    {{-- <a href="{{ route('admin.widget.list') }}"> --}}
                    <div class="card o-hidden widget-cards">
                        <div class="primary-box  card-body">
                            <div class="media static-top-widget align-items-center">
                                <div class="icons-widgets">
                                    <div class="align-self-center text-center">
                                        <i data-feather="navigation" class="font-warning"></i>
                                    </div>
                                </div>
                                <div class="media-body media-doller">
                                    <span class="m-0">Average Basket YTD</span>
                                    <h3 class="mb-0"> <span class="counter">$
                                            {{ number_format($averageBasket, 2) }}</span>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- </a> --}}
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('admin.widget.list') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="warning-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center">
                                            <i data-feather="navigation" class="font-warning"></i>
                                        </div>
                                    </div>
                                    <div class="media-body media-doller">
                                        <span class="m-0">Widget</span>
                                        <h3 class="mb-0"> <span class="counter">{{ $widget }}</span>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('admin.product.dblist') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="secondary-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center">
                                            <i data-feather="box" class="font-secondary"></i>
                                        </div>
                                    </div>
                                    <div class="media-body media-doller">
                                        <span class="m-0">Products</span>
                                        <h3 class="mb-0"><span class="counter"><?= $productCount ?></span><small> </small>
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('admin.customer.messagelist') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="primary-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center"><i data-feather="message-square"
                                                class="font-primary"></i></div>
                                    </div>
                                    <div class="media-body media-doller"><span class="m-0">Messages</span>
                                        <h3 class="mb-0"><span class="counter">{{ $contactMsg }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('admin.customer') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="danger-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center"><i data-feather="users"
                                                class="font-danger"></i></div>
                                    </div>
                                    <div class="media-body media-doller"><span class="m-0">New Customers</span>
                                        <h3 class="mb-0"><span class="counter">{{ $users }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('sale.orders') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="primary-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center"><i data-feather="users"
                                                class="font-danger"></i></div>
                                    </div>
                                    <div class="media-body media-doller"><span class="m-0">Orders</span>
                                        <h3 class="mb-0"><span class="counter">{{ $orders }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-xxl-3 col-md-6 xl-50">
                    <a href="{{ route('sale.transactions') }}">
                        <div class="card o-hidden widget-cards">
                            <div class="secondary-box card-body">
                                <div class="media static-top-widget align-items-center">
                                    <div class="icons-widgets">
                                        <div class="align-self-center text-center"><i data-feather="users"
                                                class="font-danger"></i></div>
                                    </div>
                                    <div class="media-body media-doller"><span class="m-0">Transactions</span>
                                        <h3 class="mb-0"><span class="counter">{{ $trnsactions }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>


            </div>
            <div class="row">
                <div class="col-xl-6 xl-100">
                    <div class="card">
                        <div class="card-header">
                            <h5>Latest Orders</h5>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="icofont icofont-simple-left"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="user-status table-responsive latest-order-table">
                                <table class="table table-bordernone">
                                    <thead>
                                        <tr>
                                            <th scope="col">Order ID</th>
                                            <th scope="col">Order Total</th>
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($latest_orders as $latestOrder)
                                            <tr>
                                                <td>{{ $latestOrder->order_id }}</td>
                                                <td class="digits">${{ $latestOrder->amount }}</td>
                                                <td>{{ $latestOrder->method }}</td>
                                                <td
                                                    class="font-{{ $latestOrder->status == 'SUCCESS' ? 'success' : 'primary' }}">
                                                    {{ $latestOrder->status }}</td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                                <a href="{{ route('sale.orders') }}" class="btn btn-primary mt-4">View All Orders</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 xl-100">
                    <div class="card">
                        <div class="card-header">
                            <h5>Latest Transactions</h5>
                            <div class="card-header-right">
                                <ul class="list-unstyled card-option">
                                    <li><i class="icofont icofont-simple-left"></i></li>
                                    <li><i class="view-html fa fa-code"></i></li>
                                    <li><i class="icofont icofont-maximize full-card"></i></li>
                                    <li><i class="icofont icofont-minus minimize-card"></i></li>
                                    <li><i class="icofont icofont-refresh reload-card"></i></li>
                                    <li><i class="icofont icofont-error close-card"></i></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="user-status table-responsive latest-order-table">
                                <table class="table table-bordernone">
                                    <thead>
                                        <tr>
                                            <th scope="col">Reference No</th>
                                            {{-- <th scope="col">Order ID</th> --}}
                                            <th scope="col">Order Total</th>
                                            <th scope="col">Payment Method</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $latest_transaction)
                                            <tr>
                                                <td>{{ $latest_transaction->ref_num }}</td>
                                                {{-- <td>{{ $latest_transaction->order_id }}</td> --}}
                                                <td class="digits">${{ $latestOrder->amount }}</td>
                                                <td>{{ $latestOrder->method }}</td>
                                                <td
                                                    class="font-{{ $latestOrder->status == 'SUCCESS' ? 'success' : 'primary' }}">
                                                    {{ $latestOrder->status }}</td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                                <a href="{{ route('sale.transactions') }}" class="btn btn-primary mt-4">View All</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
{{-- {!! $ring_sizer['content'] !!} --}}
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
