<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width = 375, initial-scale = -1">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Cart Items</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	</head>
	<body>
		<center>
			<table border="0" style="background-color: #eaeaea;" width="600" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td height="40"></td>
				</tr>
				<tr>
					<td align="center"><a href="#"><img src="{{ $siteinfo->logo }}" alt="{{ $siteinfo->logo_alt }}" /></a></td>
				</tr>
				<tr>
					<td height="40"></td>
				</tr>
				<tr>
					<td>
						<table style="background-color: #fff;" border="0" width="550" cellspacing="0" cellpadding="0" align="center">
							<tr>
								<td height="30"></td>
							</tr>
							<tr>
								<td>
									<table border="0" width="500" cellspacing="0" cellpadding="0" align="center">
										<tr>
											<td align="center" style="font-family: 'Montserrat', sans-serif;
											font-weight: bold;
											text-transform: capitalize;
											font-size: 25px;
											line-height: normal;
											color: #000;">Products</td>
										</tr>
										<tr>
											<td height="10"></td>
										</tr>
										<tr>
											<td align="center" style="font-family: 'Montserrat', sans-serif;
											font-size: 12px;
											line-height: 20px;
											color: #000;">{!! $emailContent->content; !!}</td>
										</tr>
										<tr>
											<td height="20"></td>
										</tr>
										<tr>
											<td align="center">
												<table border="0" width="100" cellspacing="0" cellpadding="0" align="center">
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
											<td>
												<table border="0" width="100" cellspacing="0" cellpadding="0" align="center">

                                                    @foreach ($cartdata as $cartitems)

                                                    <tr>

                                                        @if ($cartitems->product_type =='ring_diamond')
                                                        @php
                                                            $image=  getProductImages($cartitems->ring_id,$cartitems->ring_color);
                                                            $diamondImg=  getDiamondImages($cartitems->diamond_id, $cartitems->diamond_type);
                                                         @endphp
                                                            <td>
                                                                <img src="{{ $image; }}" alt="Ring Image" style="max-width: 200px;"/>
                                                            </td>
                                                            <td>
                                                                <img src="{{ $diamondImg->image_url }}" alt="Diamond Image" style="max-width: 200px;"/>
                                                            </td>
                                                        @elseif ($cartitems->product_type =='ring_gemstone')
                                                        @php
                                                            $image=  getProductImages($cartitems->ring_id,$cartitems->ring_color);
                                                            $gemimage=  getGemStoneImages($cartitems->gemstone_id);
                                                        @endphp
                                                            <td>
                                                                <img src="{{ $image; }}" alt="Ring Image" style="max-width: 200px;"/>
                                                            </td>
                                                            <td>
                                                                <img src="{{ $gemimage->image_url; }}" alt="Gemstone Image" style="max-width: 200px;"/>
                                                            </td>

                                                        @elseif ($cartitems->product_type =='diamond')
                                                        @php
                                                          $diamondImg=  getDiamondImages($cartitems->diamond_id, $cartitems->diamond_type);
                                                        @endphp
                                                            <td>
                                                                <img src="{{ $diamondImg->image_url }}" alt="Diamond Image" style="max-width: 200px;"/>
                                                            </td>
                                                        @elseif ($cartitems->product_type =='gemstone')
                                                        @php
                                                            $gemimage=  getGemStoneImages($cartitems->gemstone_id);
                                                        @endphp
                                                            <td>
                                                                <img src="{{ $gemimage->image_url }}" alt="Ring Image" style="max-width: 200px;"/>
                                                            </td>
                                                        @elseif ($cartitems->product_type =='matching_set')
                                                        <?php $text = 'matching set'; ?>
                                                        @php
                                                             $image=  getProductImages($cartitems->ring_id,$cartitems->ring_color);
                                                        @endphp
                                                            <td>
                                                                <img src="{{ $image }}" alt="Ring Image" style="max-width: 200px;"/>
                                                            </td>
                                                        @endif


                                                        <tr><td align="center" style="font-family: 'Montserrat', sans-serif;
                                                            font-size: 16px;
                                                            font-weight: 600;
                                                            letter-spacing: 0;
                                                        color: #000;">PRICE : ${{ round($cartitems->ring_price) +  round($cartitems->diamond_price) + round($cartitems->gemstone_price) }}</td></tr>





														{{-- <td>
															<table border="0" width="100" cellspacing="0" cellpadding="0" align="center">

																<tr><td><img style="width: 250px;
																height: 230px;" src="image/work-1.jpg" alt=""/></td></tr>
																<tr>
																	<td height="10"></td>
																</tr>
																<tr><td align="center" style="font-family: 'Montserrat', sans-serif;
																	font-size: 18px;
																	font-weight: bold;
																	letter-spacing: 0;
																color: #000;">{{ $text }}</td></tr>
																<tr>
																	<td height="5"></td>
																</tr>
																<tr><td align="center" style="font-family: 'Montserrat', sans-serif;
																	font-size: 14px;
																	line-height: 20px;
																color: #000;">$100</td></tr>
															</table>
														</td> --}}

														<td style="display:table;" width="20"></td>
													</tr>
                                                    @endforeach

												</table>
											</td>
										</tr>


										<tr>
											<td height="20"></td>
										</tr>





										<tr>
											<td height="40"></td>
										</tr>

										<tr>
											<td align="center">
												<table style="font-family: 'Montserrat', sans-serif;
												color: #fff;
												text-decoration: none;
												text-transform: uppercase;
												font-weight: 600;
												background-color: #310f4c;
												border-radius: 5px;" border="0" width="220" cellspacing="0" cellpadding="0" align="center">
													<tr>
														<td height="10"></td>
													</tr>
													<tr>
														<td align="center"><a style="color: #fff;
															text-decoration: none;
															font-family: 'Montserrat', sans-serif;
														font-size: 13px;" href="{{ $siteinfo->website_url }}#/cart">Continue shopping</a></td>
													</tr>
													<tr>
														<td height="10"></td>
													</tr>
												</table>
											</td>
										</tr>



										<tr>
											<td height="40"></td>
										</tr>
										<tr>
											<td align="center" style="font-family: 'Montserrat', sans-serif;
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
									<table bgcolor="#310f4c" border="0" width="100%" cellspacing="0" cellpadding="0" align="center">
										<tr>
											<td height="10"></td>
										</tr>
										<tr>
											<td align="center" style="font-family: 'Montserrat', sans-serif;
											font-size: 12px;
											line-height: 20px;
											color: #fff;">{!! $siteinfo->copyright !!}</td>
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
	</body>
</html>
