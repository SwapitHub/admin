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
                            <h3>Order List
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
                            <li class="breadcrumb-item">Orders</li>
                            <li class="breadcrumb-item active">Order List</li>
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
                                        placeholder="Search by OrderId , Email ," value="{{ request('search') }}">
                                </div>
                                <div class="form-group">
                                    <select name="orderStatus" class="form-control">
                                        <option value="All" {{ request('orderStatus') == 'All' ? 'selected' : '' }}>All
                                        </option>
                                        <option value="Success" {{ request('orderStatus') == 'Success' ? 'selected' : '' }}>
                                            Success</option>
                                        <option value="Processing"
                                            {{ request('orderStatus') == 'Processing' ? 'selected' : '' }}>Processing
                                        </option>
                                        <option value="Cancel" {{ request('orderStatus') == 'Cancel' ? 'selected' : '' }}>
                                            Cancel</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-outline-primary">Search</button>
                                </div>
                            </form>
                            &ensp;
                            <a href="{{ route('sale.orders.export') }}" class="btn btn-outline-success mb-3">Export </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table-desi ">
                                <table class="table all-package table-hover" id="editableTable">
                                    <thead>
                                        <tr>
                                            <th>Order Id </th>
                                            <th>Order Date </th>
                                            <th>Order Status </th>
                                            <th>Custome Name</th>
                                            <th>Custome Email</th>
                                            <th>Grand Total / Paymemt Method</th>
                                            <th>Tracking No.</th>
                                            <th>Product</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($orders as $index => $order)
                                            @php
                                                $orderItem = getOrderItem($order->order_id);
                                                $redirect_url = route('sale.orders.detail', ['id' => $order->id]);
                                            @endphp
                                            <tr onclick=window.location="{{ $redirect_url }}">
                                                <td class="text-start">
                                                    {{ $order->order_id }}
                                                </td>
                                                <td class="text-start">
                                                    {{ date('M d, Y', strtotime($order->created_at)) }}
                                                </td>
                                                <td class="text-start">
                                                    <?php
                                                    if ($order->name == 'Processing') {
                                                        $color = 'secondary';
                                                    } elseif ($order->name == 'Complete') {
                                                        $color = 'success';
                                                    } elseif ($order->name == 'Failed') {
                                                        $color = 'primary';
                                                    } elseif ($order->name == 'Refunded') {
                                                        $color = 'primary';
                                                    } elseif ($order->name == 'Pending') {
                                                        $color = 'warning';
                                                    } else {
                                                        $color = 'secondary';
                                                    }
                                                    ?>

                                                    <span
                                                        class="badge badge-{{ $color }}">{{ $order->name }}</span>
                                                </td>
                                                <td class="text-start">
                                                    {{ $order->first_name }} {{ $order->last_name }}</span>
                                                </td>
                                                <td class="text-start">
                                                    {{ $order->email }}
                                                </td>

                                                <td data-field="number">$
                                                    {{ number_format($order->amount, 0, '.', '') }} /- <br>
                                                    {{ $order->method }}
                                                </td>
                                                <td data-field="number">
                                                    {{ !empty($order->tracking_number) ? $order->tracking_number : 'N/A' }}
                                                </td>
                                                <td class="d-flex">
                                                    @foreach ($orderItem as $orderItem)
                                                        <?php
                                                        $products = json_decode($orderItem->order_data);
                                                        if ($products->product_type == 'diamond') {
                                                            $diamondImage = getDiamondImages($products->diamond_id, $products->diamond_type);
                                                            ?>
                                                        <div class="d-flex border p-2">
                                                            @if ($diamondImage != null)
                                                                @isset($diamondImage->image_url)
                                                                    <img src="{{ $diamondImage->image_url }}" alt="">
                                                                @endisset
                                                            @endif
                                                        </div>
                                                        <?php

                                                        }

                                                        if ($products->product_type == 'gemstone') {
                                                            $gemstoneImage = getGemStoneImages($products->gemstone_id);
                                                            ?>
                                                        <div class="d-flex border p-2">
                                                            @if ($gemstoneImage != null)
                                                                <img src="{{ $gemstoneImage->image_url }}" alt="">
                                                            @endif

                                                        </div>
                                                        <?php
                                                        }

                                                        // if ($products->product_type == 'maching_set') {
                                                        //     echo 'maching_set';
                                                        // }

                                                        if ($products->product_type == 'ring_diamond') {
                                                            $ringImage = getProductImages($products->ring_id, $products->ring_color);
                                                            $diamondImage = getDiamondImages($products->diamond_id, $products->diamond_type);
                                                            ?>
                                                        <div class="d-flex border p-2">
                                                            <img src="{{ $ringImage }}" alt="">
                                                            @if ($diamondImage != null)
                                                                @isset($diamondImage->image_url)
                                                                    <img src="{{ $diamondImage->image_url }}" alt="">
                                                                @endisset
                                                            @endif

                                                        </div>
                                                        <?php
                                                        }

                                                        if($products->product_type == 'ring_gemstone')
                                                        {
                                                            $ringImage = getProductImages($products->ring_id, $products->ring_color);
                                                            $gemstoneImage = getGemStoneImages($products->gemstone_id);
                                                            ?>
                                                        <div class="d-flex border p-2">
                                                            <img src="{{ $ringImage }}" alt="">
                                                            @isset($gemstoneImage->image_url)
                                                                <img src="{{ $gemstoneImage->image_url }}" alt="">
                                                            @endisset

                                                        </div>
                                                        <?php

                                                        }
                                                        ?>
                                                    @endforeach
                                                </td>





                                                <td>
                                                    <a href="{{ route('sale.orders.detail', ['id' => $order->id]) }}">
                                                        <i class="fa fa-angle-right fw-bold" title="View"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                            <div
                                class="dataTables_paginate paging_simple_numbers d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of total
                                    {{ $orders->total() }} entries
                                </div>
                                <div class="float-end">
                                    {{-- <p>{{ $orders->links() }}</p> --}}
                                    <p>{{ $orders->appends(request()->query())->links() }}</p>
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
