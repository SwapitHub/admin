@extends('layouts.layout')
@section('content')
    <style>
        label {
            text-transform: capitalize;
        }
    </style>
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
                                <a href="{{ url('admin/dashboard') }}">
                                    <i data-feather="home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.homecontent') }}">Home Content</a></li>
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
                                        aria-selected="true"><i data-feather="image" class="me-2"></i>Banner</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-contact" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="activity" class="me-2"></i>Content</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="top-tabContent">
                                <div class="tab-pane fade active show" id="top-profile" role="tabpanel"
                                    aria-labelledby="top-profile-tab">
                                    <h5 class="f-w-600">Banners</h5>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card-body">
                                                <form action="{{ route('admin.createbanner') }}" method="POST"
                                                    enctype="multipart/form-data" class="needs-validation" novalidate>
                                                    @csrf
                                                    <div class="form-group row">
                                                        <label for="validationCustom0"
                                                            class="col-xl-3 col-md-4"><span>*</span>Title</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control @error('title') is-invalid @enderror"
                                                                id="title" name="title" value="{{ old('title') }}"
                                                                type="text" placeholder="Title">
                                                            @error('title')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom1" class="col-xl-3 col-md-4">Sub
                                                            Title</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control" id="name" value=""
                                                                name="subtitle" value="{{ old('subtitle') }}" type="text"
                                                                placeholder="Sub Title">
                                                            @error('subtitle')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom0"
                                                            class="col-xl-3 col-md-4">Type</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <select name="type" id="type"
                                                                onchange="showTerm(this.value)" class="form-control">
                                                                <option value="Home"
                                                                    {{ old('type') == 'Home' ? 'selected' : '' }}>Home
                                                                </option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="validationCustom2"
                                                            class="col-xl-3 col-md-4">Link</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control" id="link" placeholder="Link"
                                                                value="{{ old('link') }}" name="link"
                                                                type="url">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom2" class="col-xl-3 col-md-4">Button
                                                            Name</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control" id="btn_name"
                                                                placeholder="Custom Button Name"
                                                                value="{{ old('btn_name') }}" name="btn_name"
                                                                type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="validationCustom2"
                                                            class="col-xl-3 col-md-4"><span>*</span>Banner</label>
                                                        <div class="col-xl-8 col-md-7">
                                                            <input class="form-control dropify" id="image"
                                                                name="image" data-default-file="" type="file"
                                                                accept="image/*">
                                                            @error('image')
                                                                <style>
                                                                    .dropify-wrapper {
                                                                        border: 1px solid #dc3545;
                                                                        border-radius: 0.25rem;
                                                                    }
                                                                </style>
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-xl-3 col-md-4">Status</label>
                                                        <div class="col-md-7">
                                                            <div class="checkbox checkbox-primary">
                                                                <input id="checkbox-primary-2" type="checkbox" checked
                                                                    name="status" value="true" data-original-title="">
                                                                <label for="checkbox-primary-2">Enable the Banner</label>
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
                                        <hr class="mt-5">
                                        <div class="col-sm-12 mt-5">
                                            <div class="order-datatable">
                                                <table class="display" id="basic-1">
                                                    <thead>
                                                        <tr>
                                                            <th>Banner Id</th>
                                                            <th>Title</th>
                                                            <th>Sub Title</th>
                                                            <th>Banner</th>
                                                            <th>Status</th>
                                                            <th>Banner Type</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($bannerlist as $index=>$item)
                                                            <tr>
                                                                <td>{{ $index+1 }}</td>
                                                                <td>
                                                                    {{ $item->title }}
                                                                </td>
                                                                <td> {{ $item->subtitle }}</td>
                                                                <td>
                                                                    <center><a
                                                                            href="{{ env('AWS_URL') }}public/{{ $item->banner }}"
                                                                            alt="{{ $item->title }}"
                                                                            target="_blank"><img
                                                                                src="{{ env('AWS_URL') }}public/{{ $item->banner }}"
                                                                                alt="{{ $item->title }}"
                                                                                style="height: 100px; width:100px"></a>
                                                                    </center>
                                                                </td>
                                                                <td><span
                                                                        class="badge badge-<?= $item->status == 'true' ? 'success' : 'primary' ?>">
                                                                        {{ $item->status == 'true' ? 'Active' : 'Inactive' }}</span>
                                                                </td>
                                                                <td> {{ $item->type }}</td>
                                                                <td>
                                                                    <div>
                                                                        <a href="{{ route('admin.getedit', ['id' => $item->id]) }}"
                                                                            title="Edit Banner"><i
                                                                                class="fa fa-edit me-2 font-success"></i></a>
                                                                        <a href="javascript:void(0)"
                                                                            onclick="deleteItem('{{ url('deletebanner') }}/{{ $item->id }}')"
                                                                            title="Delete Banner"><i
                                                                                class="fa fa-trash font-danger"></i></a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="top-contact" role="tabpanel" aria-labelledby="contact-top-tab">

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
