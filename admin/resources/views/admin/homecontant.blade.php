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
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Bridal
                                        Jewelry</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-section2" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Celebrate
                                        Love with Timeless Elegance</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-section3" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Discover
                                        Matching Sets</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-section4" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Section 4</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-section5" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Love’s
                                        Brilliance</a>
                                </li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-section6" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="plus-square" class="me-2"></i>Sama
                                        Difference</a>
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
                                                            <input
                                                                class="form-control @error('title') is-invalid @enderror"
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
                                                                name="subtitle" value="{{ old('subtitle') }}"
                                                                type="text" placeholder="Sub Title">
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
                                                        @foreach ($bannerlist as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
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
                                <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">Section 1</h5>
                                    <form action="{{ route('home.section1') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf

                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading') is-invalid @enderror"
                                                    id="heading" name="heading"
                                                    value="{{ old('heading', $sectionList['section1']->heading) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description" class="summernote" cols="30" rows="10">{{ old('description', $sectionList['section1']->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section1']->image }}">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image_alt" class="form-control"
                                                    value="{{ old('image_alt', $sectionList['section1']->image_alt) }}">
                                                @error('image_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section1']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="link" class="form-control"
                                                    value="{{ old('link', $sectionList['section1']->link) }}">
                                                @error('link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-primary submitBtn">Save <i
                                                    class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-section2" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">Section 2</h5>
                                    <form action="{{ route('home.section2') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading') is-invalid @enderror"
                                                    id="heading" name="heading"
                                                    value="{{ old('heading', $sectionList['section2']->heading) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description" class="summernote" cols="30" rows="10">{{ old('description', $sectionList['section2']->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section2']->image }}">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image_alt" class="form-control"
                                                    value="{{ old('image_alt', $sectionList['section2']->image_alt) }}">
                                                @error('image_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section2']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="link" class="form-control"
                                                    value="{{ old('link', $sectionList['section2']->link) }}">
                                                @error('link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-primary submitBtn">Save <i
                                                    class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-section3" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">Section 3</h5>
                                    <form action="{{ route('home.section3') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading') is-invalid @enderror"
                                                    id="heading" name="heading"
                                                    value="{{ old('heading', $sectionList['section3']->heading) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description" class="summernote" cols="30" rows="10">{{ old('description', $sectionList['section3']->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section3']->image }}">
                                                @error('image')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image_alt" class="form-control"
                                                    value="{{ old('image_alt', $sectionList['section3']->image_alt) }}">
                                                @error('image_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section3']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="link" class="form-control"
                                                    value="{{ old('link', $sectionList['section3']->link) }}">
                                                @error('link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-primary submitBtn">Save <i
                                                    class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-section4" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">Section 4</h5>
                                    <form action="{{ route('home.section4') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading') is-invalid @enderror"
                                                    id="heading" name="heading"
                                                    value="{{ old('heading', $sectionList['section4']->heading) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description" class="summernote" cols="30" rows="10">{{ old('description', $sectionList['section4']->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image1" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section4']->image1 }}">
                                                @error('image1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image1_alt" class="form-control"
                                                    value="{{ old('image1_alt', $sectionList['section3']->image1_alt) }}">
                                                @error('image1_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image2" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section4']->image2 }}">
                                                @error('image2')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image2_alt" class="form-control"
                                                    value="{{ old('image2_alt', $sectionList['section4']->image2_alt) }}">
                                                @error('image2_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section4']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="link" class="form-control"
                                                    value="{{ old('link', $sectionList['section4']->link) }}">
                                                @error('link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-primary submitBtn">Save <i
                                                    class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-section5" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">Section 5</h5>
                                    <form action="{{ route('home.section5') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading') is-invalid @enderror"
                                                    id="heading" name="heading"
                                                    value="{{ old('heading', $sectionList['section5']->heading) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Sub
                                                heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('subheading') is-invalid @enderror"
                                                    id="subheading" name="subheading"
                                                    value="{{ old('subheading', $sectionList['section5']->subheading) }}"
                                                    type="text" placeholder="subheading">
                                                @error('subheading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description" class="summernote" cols="30" rows="10">{{ old('description', $sectionList['section5']->description) }}</textarea>
                                                @error('description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                image (desktop)</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image_desktop" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section5']->image_desktop }}">
                                                @error('image_desktop')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                Image desktop alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image_desktop_alt" class="form-control"
                                                    value="{{ old('image_desktop_alt', $sectionList['section5']->image_desktop_alt) }}">
                                                @error('image_desktop_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Banner
                                                image (mobile)</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image_mobile" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section5']->image_mobile }}">
                                                @error('image_mobile')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                Image mobile alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image_mobile_alt" class="form-control"
                                                    value="{{ old('image_mobile_alt', $sectionList['section5']->image_mobile_alt) }}">
                                                @error('image_mobile_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section5']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="link" class="form-control"
                                                    value="{{ old('link', $sectionList['section5']->link) }}">
                                                @error('link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pull-left">
                                            <button type="submit" class="btn btn-primary submitBtn">Save <i
                                                    class="fa fa-spinner fa-spin main-spinner d-none"></i></button>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="top-section6" role="tabpanel"
                                    aria-labelledby="contact-top-tab">
                                    <h5 class="f-w-600">The Sama Difference</h5>
                                    <form action="{{ route('home.section6') }}" method="POST"
                                        enctype="multipart/form-data" class="needs-validation" novalidate>
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('heading1') is-invalid @enderror"
                                                    id="heading1" name="heading1"
                                                    value="{{ old('heading1', $sectionList['section6']->heading1) }}"
                                                    type="text" placeholder="Heading">
                                                @error('heading1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4">Sub
                                                heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('subheading1') is-invalid @enderror"
                                                    id="subheading1" name="subheading1"
                                                    value="{{ old('subheading1', $sectionList['section6']->subheading1) }}"
                                                    type="text" placeholder="subheading">
                                                @error('subheading')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                Our story Image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image_desktop" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section6']->image1 }}">
                                                @error('image1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                our story alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image1_alt" class="form-control"
                                                    value="{{ old('image1_alt', $sectionList['section6']->image1_alt) }}">
                                                @error('image1_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description1" class="summernote" cols="30" rows="10">{{ old('description1', $sectionList['section6']->description1) }}</textarea>
                                                @error('description1')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name" class="form-control"
                                                    value="{{ old('btn_name', $sectionList['section6']->btn_name) }}">
                                                @error('btn_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_link" class="form-control"
                                                    value="{{ old('btn_link', $sectionList['section6']->btn_link) }}">
                                                @error('btn_link')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                Mission Image</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="image2" class="dropify"
                                                    data-default-file="{{ env('AWS_URL') }}public/{{ $sectionList['section6']->image2 }}">
                                                @error('image2')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>
                                                Mission image alt</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="image2_alt" class="form-control"
                                                    value="{{ old('image2_alt', $sectionList['section6']->image2_alt) }}">
                                                @error('image2_alt')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0"
                                                class="col-xl-3 col-md-4"><span>*</span>Description</label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="description2" class="summernote" cols="30" rows="10">{{ old('description2', $sectionList['section6']->description2) }}</textarea>
                                                @error('description2')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                name</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_name2" class="form-control"
                                                    value="{{ old('btn_name2', $sectionList['section6']->btn_name2) }}">
                                                @error('btn_name2')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Button
                                                link</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="btn_link2" class="form-control"
                                                    value="{{ old('btn_link2', $sectionList['section6']->btn_link2) }}">
                                                @error('btn_link2')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-xl-3 col-md-4">Status</label>
                                            <div class="col-md-7">
                                                <div class="checkbox checkbox-primary">
                                                    <input id="checkbox-primary-2" type="checkbox" checked name="status"
                                                        value="true" data-original-title="">
                                                    <label for="checkbox-primary-2">Enable the Section</label>
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
