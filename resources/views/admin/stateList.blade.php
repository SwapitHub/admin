@extends('layouts.layout')
@section('content')
	<div class="page-body">
		<!-- Container-fluid starts-->
		<div class="container-fluid">
			<div class="page-header">
				<div class="row">
					<div class="col-lg-6">
						<div class="page-header-left">
							<h3>{{ $title }}
								<small>Diamond Admin panel</small>
							</h3>
						</div>
					</div>
					<div class="col-lg-6">
						<ol class="breadcrumb pull-right">
							<li class="breadcrumb-item">
								<a href="{{ url('admin/dashboard') }}">
									<i data-feather="home"></i>
								</a>
							</li>
							<li class="breadcrumb-item">State</li>
							<li class="breadcrumb-item active">{{ $title }}</li>
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

							<a href="{{ $viewurl }}" class="btn btn-primary mt-md-0 mt-2">Add new</a>
						</div>

						<div class="card-body">
							<div class="table-responsive table-desi">
								<table class="all-package coupon-table table table-striped">
									<thead>
										<tr>
											<th>
												<span type="button"
												class="badge badge-primary add-row delete_all"><i class="fa fa-trash"></i></span>
											</th>
											<th>Country</th>
											<th>State Name</th>
											<th>Tex %</th>
											<th>Status</th>
											<!--th>Created at</th>
											<th>Updated at</th--->
											<th>Options</th>

										</tr>
									</thead>

									 <tbody>
									<tr><input type="hidden" value="{{ url('deletecountry') }}" name="url" id="url"></tr>
										@foreach($list as $item)
										<tr data-row-id="{{ $item->id }}">
											<td>
												<input class="checkbox_animated check-it" type="checkbox"
												value="" id="flexCheckDefault" data-id="{{ $item->id }}">
											</td>

											<td class="text-capitalize">{{ $item->country_name }}</td>
											<td class="text-capitalize">{{ $item->name }}</td>
											<td>{{ $item->tax_percentage }}</td>

											<td >
												<span class="badge badge-{{ ($item->status =='true')?'success':'primary' }}">{{ ($item->status =='true')?'Active':'Inactive' }}</span>
											</td>

											<!--td class="list-date">{{ $item->created_at }}</td>
											<td class="list-date">{{ $item->updated_at }}</td--->
											<td class="list-date">
											    <a href="{{ route($editurl, ['id' => $item->id]) }}">
													<i class="fa fa-edit" title="Edit"></i>
												</a>
											</td>
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
