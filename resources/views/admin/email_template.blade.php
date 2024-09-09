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
						<li class="breadcrumb-item"><a href="{{ route($backtrack) }}">Template List</a></li>
						<li class="breadcrumb-item active">{{ $title }} </li>
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
								aria-selected="true"><i data-feather="activity" class="me-2"></i>Email Template</a>
							</li>
						</ul>
						<div class="tab-content" id="top-tabContent">
							<div class="tab-pane fade show active" id="top-profile" role="tabpanel"
							aria-labelledby="top-profile-tab">
								<form action="{{ $url_action }}" method="POST" class="needs-validation" novalidate >
									@csrf
                                    <div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Name</label>
										<div class="col-xl-8 col-md-7">
											<input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
											value="{!! old()?old('name'):$obj['name']??'' !!}" type="text" placeholder="Name">
											@error('name')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
                                    <div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Group</label>
										<div class="col-xl-8 col-md-7">
											<select name="group" class="form-control">
                                                <option selected disabled>--select--</option>
                                                <option value="send_welcome_email" {{ $obj != null && $obj['group'] == 'send_welcome_email'?'selected':''}}>Send welcome email</option>
                                                <option value="send_account_verification_email"  {{ $obj != null && $obj['group'] == 'send_account_verification_email'?'selected':''}} >Send account verification email</option>
                                                <option value="send_registratino_email" {{ $obj != null && $obj['group'] == 'send_registratino_email'?'selected':''}}>Send registration email</option>
                                                <option value="reset_password" {{ $obj != null && $obj['group'] == 'reset_password'?'selected':''}}>Send reset email</option>
                                                {{-- <option value="send_registratino_email_to_admin" {{ $obj != null && $obj['group'] == 'send_registratino_email_to_admin'?'selected':''}}>Send registratino email to admin</option> --}}
                                                <option value="send_success_order_email_to_customer" {{ $obj != null && $obj['group'] == 'send_success_order_email_to_customer'?'selected':''}}>Send success order email to customer</option>
                                                <option value="send_failed_order_email_to_customer" {{ $obj != null && $obj['group'] == 'send_failed_order_email_to_customer'?'selected':''}}>Send failed order email to customer</option>
                                                <option value="send_abandoned_cart_email_to_customer" {{ $obj != null && $obj['group'] == 'send_abandoned_cart_email_to_customer'?'selected':''}}>Send email to customer for abandoned Cart</option>
                                                {{-- <option value="send_success_order_email_to_admin" {{ $obj != null && $obj['group'] == 'send_success_order_email_to_admin'?'selected':''}}>Send success order email to admin</option> --}}
                                                {{-- <option value="send_email_contant_admin" {{ $obj != null && $obj['group'] == 'send_email_contant_admin'?'selected':''}}>Send email contant admin</option> --}}
                                                <option value="other">Other</option>
                                            </select>
											@error('group')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>
                                    <div class="form-group row">
										<label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Content</label>
										<div class="col-xl-8 col-md-7">
                                            <textarea name="content" class="form-control" style="margin:0px !important" rows="10">{!! old()?old('content'):$obj['content']??'' !!}</textarea>
											@error('content')
											<div class="invalid-feedback">{{ $message }}</div>
											@enderror
										</div>
									</div>

									<div class="form-group row">
										<label class="col-xl-3 col-md-4">Status</label>
										<div class="col-md-7">
											<div class="checkbox checkbox-primary">
												<input id="checkbox-primary-2"  type="checkbox" name="status"
												value="true" data-original-title="" {{ old('status') == 'true' || (isset($obj) && is_object($obj) && $obj->status == 'true') ? 'checked' : '' }} >
												<label for="checkbox-primary-2">Enable the Template</label>
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

