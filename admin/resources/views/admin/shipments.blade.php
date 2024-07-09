@extends('layouts.layout')
@section('content')
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="page-header-left">
                            <h3>Shipments
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
                            <li class="breadcrumb-item">Shipments</li>
                            <li class="breadcrumb-item active">Shipment List</li>
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
                                            <th>Custome Name</th>
                                            <th>Custome Address</th>
                                            <th>Tracking No</th>
                                            <th>Shipping Status</th>
                                            <th>Shipment Date</th>
                                            {{-- <th>Transaction Id</th> --}}
                                            <th>Payment Method</th>
                                            <th>Estimated Date of Delivery </th>
                                            {{-- <th>Delivery Status</th> --}}
                                            <th>Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($list as $item)
                                            <tr>
                                                <td><a href="{{ route('shipment.detail', ['id' => $item->order_id]) }}"
                                                        style="text-decoration:underline;color:blue !important">{{ $item->order_id }}</a>
                                                </td>

                                                <td>{{ $item->username }}</td>
                                                <td>{{  $item['useraddress']['address_line1'] }} {{ $item['useraddress']['city']  }} , {{ $item['useraddress']['state']  }} , {{ $item['useraddress']['country']  }}</td>
                                                <td>{{ $item->tracking_number }}</td>

                                                @if ($item->name == 'Pending')
                                                    @php $color = 'secondary';@endphp
                                                @endif
                                                <td><span class="badge badge-secondary">{{ $item->name }}</span>
                                                </td>

                                                {{-- <td>{{ $item->tracking_number }}</td> --}}
                                                <td>{{ date('M d, Y', strtotime($item->created_at)) }}</td>
                                                {{-- <td>{{ $item->transaction_id }}</td> --}}


                                                <td>Card Payment</td>
                                                <td>ss</td>



                                                <td>${{ number_format($item->amount, 2, '.', '') }}/-</td>
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
