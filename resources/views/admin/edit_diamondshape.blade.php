@extends('layouts.layout')
@section('content')
<div class="page-body">
	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="page-header">
			<div class="row">
				<div class="col-lg-6">
					<div class="page-header-left">
						<h3>Edit diamond shape
							<small>Dimond Admin panel</small>
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
						<li class="breadcrumb-item"><a href="{{ url('diamondshape') }}">Diamond shape List</a></li>
						<li class="breadcrumb-item active">Edit shape</li>
					</ol>
				</div>
			</div>
		</div>
	</div>
	<!-- Container-fluid Ends-->

	<!-- Container-fluid starts-->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xl-12">
				<div class="card tab2-card">
					<div class="card-body">
						<ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
							<li class="nav-item"><a class="nav-link active" id="top-profile-tab" data-bs-toggle="tab"
								href="#top-profile" role="tab" aria-controls="top-profile"
								aria-selected="true"><i data-feather="box" class="me-2"></i>Diamond shape</a>
							</li>
						</ul>
						<div class="tab-content" id="top-tabContent">
							<div class="tab-pane fade show active" id="top-profile" role="tabpanel"
							aria-labelledby="top-profile-tab">
								<form action="{{ route('admin.updateshape') }}" method="POST" class="needs-validation" enctype="multipart/form-data">
									@csrf
									<input type="hidden" name="id" value="{{ $shapedata->id }}">
									<div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4">Shape</label>
										<div class="col-xl-8 col-md-7">
											<input class="form-control" id="shape" name="shape"
											value="{{ old('shape',$shapedata->shape) }}" type="text" placeholder="Diamond Shape">
											@error('shape')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
                                    <div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Browse Name</label>
										<div class="col-xl-8 col-md-7">
											<input class="form-control" id="name" name="name"
											value="{{ old('name',$shapedata->name) }}" type="text" placeholder="Diamond Shape Name">
											@error('name')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
                                    {{-- <div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4">Redirect to </label>
										<div class="col-xl-8 col-md-7">
											<input class="form-control" id="redirect_to" name="redirect_to"
											value="{{ old('redirect_to',$shapedata->redirect_to) }}" type="text" placeholder="Redirect Url">
											@error('redirect_to')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div> --}}
									<div class="form-group row">
										<label for="validationCustom1" class="col-xl-3 col-md-4">Extension </label>
										<div class="col-xl-8 col-md-7">
											<input class="form-control" id="slug" name="slug"
											value="{{ old('slug') }}" type="text" placeholder="{{ $shapedata->slug }}">
												<small>(Leave blank if you do not want to update)</small>
											@error('slug')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
									<div class="form-group row">
										<label for="validationCustom4" class="col-xl-3 col-md-4">
										Image</label>
										<div class="col-xl-8 col-md-7">
											<input type="file" name="image" data-default-file="{{ env('AWS_URL') }}public/{{ $shapedata->icon }}" class="form-control dropify">
											@error('image')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>

									</div>
									<div class="form-group row">
										<label for="validationCustom4" class="col-xl-3 col-md-4">
											Order</label>
										<div class="col-xl-8 col-md-7">
											<input type="text" name="order_number" value="{{ old('order_number',$shapedata->order_number) }}" class="form-control">
										</div>

									</div>
									<div class="form-group row">

										<label class="col-xl-3 col-md-4">Status   </label>
										<div class="col-md-7">
											<div class="checkbox checkbox-primary">
												<input id="checkbox-primary-2" type="checkbox" name="status"
												value="true" data-original-title="" {{ ($shapedata->status =='true')?'checked':'' }}>
												<label for="checkbox-primary-2">Enable the shape</label>
											</div>
										</div>
									</div>
									<div class="pull-left">
										<button type="submit" class="btn btn-primary submitBtn">Save <i
										class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
									</div>
								</form>

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
