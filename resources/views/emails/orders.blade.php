<!doctype html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Spectral:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
    </style>
</head>

<body>
    <center>
        <table border="0" style="background-color: #eaeaea;" width="600" cellspacing="0" cellpadding="0"
            align="center">
            <tr>
                <td height="40"></td>
            </tr>
            <tr>
                <td align="center"><a href="#"><img src="{{ $siteinfo->logo }}"
                            alt="{{ $siteinfo->logo_alt }}" /></a></td>
            </tr>
            <tr>
                <td height="40"></td>
            </tr>
            <tr>
                <td>
                    <table style="background-color: #fff;" border="0" width="550" cellspacing="0" cellpadding="0"
                        align="center">
                        <tr>
                            <td height="30"></td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" width="500" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                        <td align="center"
                                            style="font-family: 'Montserrat', sans-serif;
                                    font-weight: bold;
                                    text-transform: capitalize;
                                    font-size: 25px;
                                    line-height: normal;
                                    color: #000;">
                                            New Order: #{{ $ordervalue->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td height="10"></td>
                                    </tr>
                                    <tr>
                                        <td align="center"
                                            style="font-family: 'Montserrat', sans-serif;
                                    font-size: 12px;
                                    line-height: 20px;
                                    color: #000;">
                                            {!! $emailContent->content !!}</td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td align="center">
                                            <table border="0" width="100" cellspacing="0" cellpadding="0"
                                                align="center">
                                                <tr>
                                                    <td height="1" bgcolor="#d0d0d0"></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td id="" style="background-color:#fff" valign="top" bgcolor="#fff">
                                            <table width="100%" cellspacing="0" cellpadding="20" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top" style="padding:0;">
                                                            <div id=""
                                                                style="color:#636363;font-family: Montserrat;font-size:14px;line-height:150%;text-align:left"
                                                                align="left">
                                                                <h2
                                                                    style="color:#7f54b3;display:block;font-family: Montserrat;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                                    <a href="#"
                                                                        style="font-weight:normal;text-decoration:underline;color:#7f54b3"
                                                                        target="_blank">[Order
                                                                        #{{ $ordervalue->order_id }}]</a>
                                                                    ({{ date('M d,Y', strtotime($ordervalue->created_at)) }})
                                                                </h2>
                                                                <div style="margin-bottom:40px">
                                                                    <table
                                                                        style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family: Montserrat;"
                                                                        width="100%" cellspacing="0" cellpadding="6"
                                                                        border="1">
                                                                        <thead>
                                                                            <tr>
                                                                                <th scope="col"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Product</th>
                                                                                <th scope="col"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Product Description
                                                                                </th>
                                                                                <th scope="col"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Price</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            @foreach ($orderItem as $orderItem)
                                                                                @php
                                                                                    $orderdata = json_decode(
                                                                                        $orderItem['order_data'],
                                                                                    );
                                                                                @endphp

                                                                                <tr>
                                                                                    @php
                                                                                        $products = json_decode(
                                                                                            $orderItem->order_data,
                                                                                        );
                                                                                        if (
                                                                                            !empty(
                                                                                                $products->ring_id
                                                                                            ) &&
                                                                                            !empty(
                                                                                                $products->diamond_id
                                                                                            )
                                                                                        ) {
                                                                                            $ringImages[
                                                                                                $orderItem->id
                                                                                            ] = getProductImages(
                                                                                                $products->ring_id,
                                                                                                $products->ring_color,
                                                                                            );
                                                                                            $diamondImages[
                                                                                                $orderItem->id
                                                                                            ] = getDiamondImages(
                                                                                                $products->diamond_id,
                                                                                                $products->diamond_type,
                                                                                            );
                                                                                        } elseif (
                                                                                            !empty(
                                                                                                $products->ring_id
                                                                                            ) &&
                                                                                            !empty(
                                                                                                $products->gemstone_id
                                                                                            )
                                                                                        ) {
                                                                                            $ringImages[
                                                                                                $orderItem->id
                                                                                            ] = getProductImages(
                                                                                                $products->ring_id,
                                                                                                $products->ring_color,
                                                                                            );
                                                                                            $gemstoneImages[
                                                                                                $orderItem->id
                                                                                            ] = getGemStoneImages(
                                                                                                $products->gemstone_id,
                                                                                            );
                                                                                        } elseif (
                                                                                            !empty(
                                                                                                $products->diamond_id
                                                                                            )
                                                                                        ) {
                                                                                            $diamondImages[
                                                                                                $orderItem->id
                                                                                            ] = getDiamondImages(
                                                                                                $products->diamond_id,
                                                                                                $products->diamond_type,
                                                                                            );
                                                                                        } elseif (
                                                                                            !empty(
                                                                                                $products->gemstone_id
                                                                                            )
                                                                                        ) {
                                                                                            $gemstoneImages[
                                                                                                $orderItem->id
                                                                                            ] = getGemStoneImages(
                                                                                                $products->gemstone_id,
                                                                                            );
                                                                                        }
                                                                                    @endphp
                                                                                    <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family: Montserrat;word-wrap:break-word"
                                                                                        align="left">
                                                                                        {{-- product Name --}}
                                                                                        @php
                                                                                            $ringImage = isset(
                                                                                                $ringImages[
                                                                                                    $orderItem->id
                                                                                                ],
                                                                                            )
                                                                                                ? $ringImages[
                                                                                                    $orderItem->id
                                                                                                ]
                                                                                                : null;
                                                                                            $diamondImage = isset(
                                                                                                $diamondImages[
                                                                                                    $orderItem->id
                                                                                                ],
                                                                                            )
                                                                                                ? $diamondImages[
                                                                                                    $orderItem->id
                                                                                                ]
                                                                                                : null;
                                                                                            $gemstoneImage = isset(
                                                                                                $gemstoneImages[
                                                                                                    $orderItem->id
                                                                                                ],
                                                                                            )
                                                                                                ? $gemstoneImages[
                                                                                                    $orderItem->id
                                                                                                ]
                                                                                                : null;
                                                                                        @endphp
                                                                                        @if ($ringImage)
                                                                                            <img src="{{ $ringImage }}"
                                                                                                alt=""
                                                                                                class="blur-up lazyload border"
                                                                                                style="width:60px; height: 60px; border-radius:5px">
                                                                                            &nbsp;
                                                                                        @endif
                                                                                        @if ($diamondImage)
                                                                                            @isset($diamondImage->image_url)
                                                                                                <img src="{{ $diamondImage->image_url }}"
                                                                                                    alt=""
                                                                                                    class="blur-up lazyload border"
                                                                                                    style="width:60px; height: 60px; border-radius:5px">
                                                                                            @endisset
                                                                                            &nbsp;
                                                                                        @endif
                                                                                        @if ($gemstoneImage)
                                                                                            <img src="{{ $gemstoneImage->image_url }}"
                                                                                                alt=""
                                                                                                class="blur-up lazyload border"
                                                                                                style="width:60px; height: 60px;  border-radius:5px">
                                                                                        @endif
                                                                                    </td>
                                                                                    <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family: Montserrat;word-wrap:break-word"
                                                                                        align="left">
                                                                                        <h5>
                                                                                            @if (isset($products->ring_id))
                                                                                                {{ getRingName($products->ring_id) }}
                                                                                                <br>
                                                                                            @endif
                                                                                            @if ($diamondImage)
                                                                                                {{ $diamondImage->shape }}
                                                                                                {{ $diamondImage->size }}
                                                                                            @endif
                                                                                            @if ($gemstoneImage)
                                                                                                {{ $gemstoneImage->shape }}
                                                                                                {{ $gemstoneImage->size }}
                                                                                            @endif
                                                                                        </h5>

                                                                                    </td>
                                                                                    <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family: Montserrat;"
                                                                                        align="left">
                                                                                        <span><span>$</span>{{ number_format(round($products->ring_price + $products->diamond_price + $products->gemstone_price, 2), 0, '.', '') }}</span>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach

                                                                        </tbody>

                                                                        <tfoot>
                                                                            <tr>
                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;"
                                                                                    align="left">
                                                                                    <span><span>$</span>{{ $ordervalue->amount }}</span>
                                                                                </td>
                                                                                <th scope="row" colspan="2"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;"
                                                                                    align="left">Subtotal:</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left"> {{ ($ordervalue->shipping == 0)?'Free shipping':'$'.$ordervalue->shipping }}</td>
                                                                                <th scope="row" colspan="2"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Shipping:</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left"> ${{ ($ordervalue->tax == 0)?0:$ordervalue->tax }}</td>
                                                                                <th scope="row" colspan="2"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Tax:</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">{{ $ordervalue->method }}
                                                                                </td>
                                                                                <th scope="row" colspan="2"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Payment method:</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">
                                                                                    <span><span>$</span>{{ $ordervalue->amount + $ordervalue->shipping  + $ordervalue->tax }}</span>
                                                                                </td>
                                                                                <th scope="row" colspan="2"
                                                                                    style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left"
                                                                                    align="left">Total:</th>
                                                                            </tr>
                                                                        </tfoot>
                                                                    </table>
                                                                </div>
                                                                <table id=""
                                                                    style="width:100%;vertical-align:top;margin-bottom:40px;padding:0"
                                                                    width="100%" cellspacing="0" cellpadding="0"
                                                                    border="0">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td style="text-align:left;font-family: Montserrat;border:0;padding:0"
                                                                                width="50%" valign="top"
                                                                                align="left">
                                                                                <h2
                                                                                    style="color:#7f54b3;display:block;font-family: Montserrat;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                                                    Billing address</h2>
                                                                                <address
                                                                                    style="padding:12px;color:#636363;border:1px solid #e5e5e5; min-height: 106px;">
                                                                                    {{ trim($billing_address->first_name) }}  {{ trim($billing_address->last_name) }} <br>
                                                                                    {{ $billing_address->address_line1 }}<br>{{$billing_address->city }},{{$billing_address->state }}, {{$billing_address->zipcode }} <br>{{$billing_address->country }} <br>
                                                                                    phone : {{ $billing_address->phone }}
                                                                                </address>
                                                                            </td>
                                                                            <td style="text-align:left;font-family: Montserrat;padding:0"
                                                                                width="50%" valign="top"
                                                                                align="left">
                                                                                <h2
                                                                                    style="color:#7f54b3;display:block;font-family: Montserrat;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">
                                                                                    Shipping address</h2>
                                                                                <address
                                                                                style="padding:12px;color:#636363;border:1px solid #e5e5e5; min-height: 106px;">
                                                                                {{ trim($shipping_address->first_name) }}  {{ trim($shipping_address->last_name) }} <br>
                                                                                {{ $shipping_address->address_line1 }}<br>{{$shipping_address->city }},{{$shipping_address->state }}, {{$billing_address->zipcode }} <br>{{$billing_address->country }} <br>
                                                                                phone : {{ $shipping_address->phone }}
                                                                                </address>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <p
                                                                    style="margin:0 0 16px; font-size: 12px;
color: #000;">
                                                                    Congratulations on the sale.</p>
                                                                <p style="font-size: 12px;
color: #000;"> <a
                                                                        href="#"
                                                                        style="color:#7f54b3;font-weight:normal;text-decoration:underline; font-size: 12px;
color: #000;"
                                                                        target="_blank">Collect payments easily</a>
                                                                    from your customers anywhere with our mobile app.
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="40"></td>
                                    </tr>
                                    <tr>
                                        <td align="center"
                                            style="font-family: 'Montserrat', sans-serif;
                                    font-size: 12px;
                                    line-height: 20px;
                                    color: #000;">
                                            Thanks,<br>
                                            The {!! $siteinfo->name !!} Team
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height="30"></td>
                        </tr>
                        <tr>
                            <td>
                                <table bgcolor="#310f4c" border="0" width="100%" cellspacing="0"
                                    cellpadding="0" align="center">
                                    <tr>
                                        <td height="10"></td>
                                    </tr>
                                    <tr>
                                        <td align="center"
                                            style="font-family: 'Montserrat', sans-serif;
                                    font-size: 12px;
                                    line-height: 20px;
                                    color: #fff;">
                                            {!! $siteinfo->copyright !!}</td>
                                    </tr>
                                    <tr>
                                        <td height="10"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td height="40"></td>
            </tr>
        </table>
    </center>
