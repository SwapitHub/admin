@extends('layouts.layout')
@section('content')
<style>
    .card .card-header form {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: flex-end;
        width: 100%;
    }
</style>
<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-lg-6">
                    <div class="page-header-left">
                        <h3>Transactions
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
                        <li class="breadcrumb-item">Order management</li>
                        <li class="breadcrumb-item active">Transactions</li>
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
                        <form method="GET">
                                <div class="form-group">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Search by OrderId , Transaction Id  , Reference No." value="{{ request('search') }}">
                                </div>
                                <div class="form-group">
                                    <select name="txnstatus" class="form-control">
                                        <option value="All" {{ request('txnstatus') == 'All' ? 'selected' : '' }}>All</option>
                                        <option value="Success" {{ request('txnstatus') == 'Success' ? 'selected' : '' }}>Success</option>
                                        <option value="Failed" {{ request('txnstatus') == 'Failed' ? 'selected' : '' }}>Failed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                                    <small>( From date )</small>
                                </div>
                                <div class="form-group">
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                                    <small>( To date )</small>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-outline-primary">Search</button>
                                    <a href="{{ route('sale.transactions') }}" class="btn btn-outline-secondary">clear</a>
                                </div>
                            </form>
                        </form>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive table-desi">
                            <table class="table trans-table all-package">
                                <thead>
                                    <tr>
                                        <th>Order Id</th>
                                        <th>Transaction Id</th>
                                        <th>Reference No.</th>
                                        <th>Date</th>
                                        <th>Payment Method</th>
                                        <th>Txn Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($transactions as $index=>$transaction)
                                    <tr>
                                        <td><a href="{{ route('sale.orders.detail',['id'=>$transaction->order_id]) }}" style="text-decoration:underline;color:blue !important">{{ $transaction->order_id }}</a></td>

                                        <td>{{ $transaction->transaction_id }}</td>
                                        <td>{{ is_null($transaction->ref_num) || empty($transaction->ref_num)?"NA":$transaction->ref_num }}</td>

                                        <td>{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>

                                        <td>{{ $transaction->paymanet_method }}</td>

                                        <td><span class="badge badge-{{ $transaction->status =='SUCCESS'?'success':'primary'; }}">{{ $transaction->status }}</span></td>

                                        <td>${{ number_format($transaction->amount,0,'.','') }}/-</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="dataTables_paginate paging_simple_numbers d-flex justify-content-between align-items-center">
							<div>
								Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of total {{$transactions->total()}} entries
							</div>
							<div class="float-end">
								<p>{{ $transactions->links() }}</p>
							</div>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
</div>
@endsection
