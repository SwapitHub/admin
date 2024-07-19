@extends('layouts.layout')
@section('content')
<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>Refund
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
                        <li class="breadcrumb-item">Refund</li>
                        <li class="breadcrumb-item active">Refund List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-desi">
                            <table class="table trans-table all-package">
                                <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Order Date</th>
                                        <th>Return Status</th>
                                        <th>Custome Name</th>
                                        <th>Custome Email</th>
                                        <th>Grand total</th>
                                        <th>Payment Method</th>
                                        <th>Refund Date</th>

                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($list as $item)
                                    <tr>
                                        <td>{{ $item->order_id }}</td>
                                        <td>{{ date('M d, Y', strtotime($item->order_created_at)) }}</td>
                                        <td>Status</td>
                                        <td>{{ $item->first_name }} {{ $item->last_name }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ number_format($item->amount,0,'.','') }}/-</td>
                                        <td>Card Payment</td>
                                        <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                        <td><a href="{{ route('sale.orders.detail',['id'=>$item->order_id]) }}"><i class="fa fa-eye fa-2x"></i></a></td>
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
@endsection
