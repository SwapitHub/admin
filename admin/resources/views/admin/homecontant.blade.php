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
                                                        @foreach ($bannerlist as $item)
                                                            <tr>
                                                                <td>{{ $item->id }}</td>
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
                                    <form action="{{ $url_action }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="validationCustom4" class="col-xl-3 col-md-4">
                                            <span>*</span>Main banner</label>
                                            <div class="col-xl-8 col-md-7">
                                                {{-- <input type="file" name="image" data-default-file="{{ old('main_banner') ? old('main_banner') : (isset($obj) && is_object($obj) && isset($obj->main_banner) ? asset('storage/app/public/' . $obj->main_banner) : '') }}"  class="form-control dropify"> --}}
                                                <input type="file" name="main_banner" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->main_banner }}"  class="form-control dropify">
                                                @error('main_banner')
                                                <style> .dropify-wrapper { border:1px solid red;border-radius: 0.25rem; } </style>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom0" class="col-xl-3 col-md-4"><span>*</span>Main banner title</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control @error('name') is-invalid @enderror" id="main_banner_title" name="main_banner_title"
                                                value="{!! old()?old('main_banner_title'):$obj['main_banner_title']??'' !!}" type="text" placeholder="">
                                                @error('main_banner_title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">main banner subtitle </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control" id="main_banner_subtitle" name="main_banner_subtitle"
                                                value="{{ old('main_banner_subtitle',$obj['main_banner_subtitle']) }}" type="main_banner_subtitle">
                                                @error('main_banner_subtitle')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">main banner links </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control" id="main_banner_links" name="main_banner_links"
                                                value="{{ old('main_banner_links',$obj['main_banner_links']) }}" type="text" placeholder="">
                                                @error('main_banner_links')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom4" class="col-xl-3 col-md-4">
                                            <span>*</span>sale_banner</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="sale_banner" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->sale_banner }}"  class="form-control dropify">
                                                @error('sale_banner')
                                                <style> .dropify-wrapper { border:1px solid red;border-radius: 0.25rem; } </style>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">sale banner alt </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control" id="sale_banner_alt" name="sale_banner_alt"
                                                value="{{ old('sale_banner_alt',$obj['sale_banner_alt']) }}" type="text" >
                                                @error('sale_banner_alt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom4" class="col-xl-3 col-md-4">
                                            sale banner heading</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="sale_banner_heading" value="{!! old()?old('sale_banner_heading'):$obj['sale_banner_heading']??'sale_banner_heading' !!}" class="form-control">
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">sale_banner_link </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input class="form-control" id="sale_banner_link" name="sale_banner_link"
                                                value="{{ old('sale_banner_link',$obj['sale_banner_link']) }}" type="text" >
                                                @error('sale_banner_link')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">sale banner description </label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="sale_banner_desc" class="summernote">{{ old('sale_banner_desc',$obj['sale_banner_desc']) }}</textarea>
                                                @error('sale_banner_desc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">promotion banner </label>
                                            <div class="col-xl-8 col-md-7">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_desktop_1" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_desktop_1 }}">
                                                       <small>( desktop image )</small>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_mobile_1" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_mobile_1 }}">
                                                        <small>( mobile image )</small>
                                                   </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner alt 1 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_alt_1" class="form-control" value="{{ old('ring_promotion_banner_alt_1',$obj['ring_promotion_banner_alt_1']) }}">
                                                @error('ring_promotion_banner_alt_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner title 1 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_title_1" class="form-control" value="{{ old('ring_promotion_banner_title_1',$obj['ring_promotion_banner_title_1']) }}">
                                                @error('ring_promotion_banner_title_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner description 1 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="ring_promotion_banner_desc_1" class="summernote">{{ old('ring_promotion_banner_desc_1',$obj['ring_promotion_banner_desc_1']) }}</textarea>
                                                @error('ring_promotion_banner_desc_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner link 1 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_link_1" class="form-control" value="{{ old('ring_promotion_banner_link_1',$obj['ring_promotion_banner_link_1']) }}">
                                                @error('ring_promotion_banner_link_1')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner 2 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_desktop_2" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_desktop_2 }}">
                                                       <small>( desktop image )</small>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_mobile_2" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_mobile_2 }}">
                                                        <small>( mobile image )</small>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner alt 2 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_alt_2" class="form-control" value="{{ old('ring_promotion_banner_alt_2',$obj['ring_promotion_banner_alt_2']) }}">
                                                @error('ring_promotion_banner_alt_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner title 2 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_title_2" class="form-control" value="{{ old('ring_promotion_banner_title_2',$obj['ring_promotion_banner_title_2']) }}">
                                                @error('ring_promotion_banner_title_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner description 2 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="ring_promotion_banner_desc_2" class="summernote">{{ old('ring_promotion_banner_desc_2',$obj['ring_promotion_banner_desc_2']) }}</textarea>
                                                @error('ring_promotion_banner_desc_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner link 2 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_link_2" class="form-control" value="{{ old('ring_promotion_banner_link_2',$obj['ring_promotion_banner_link_2']) }}">
                                                @error('ring_promotion_banner_link_2')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner 3 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <div class="row">
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_desktop_3" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_desktop_3 }}">
                                                       <small>( desktop image )</small>
                                                   </div>
                                                   <div class="col-md-6">
                                                       <input type="file" name="ring_promotion_banner_mobile_3" class="dropify" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_mobile_3 }}">
                                                        <small>( mobile image )</small>
                                                   </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner alt 3 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_alt_3" class="form-control" value="{{ old('ring_promotion_banner_alt_3',$obj['ring_promotion_banner_alt_3']) }}">
                                                @error('ring_promotion_banner_alt_3')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner title 3 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_title_3" class="form-control" value="{{ old('ring_promotion_banner_title_3',$obj['ring_promotion_banner_title_3']) }}">
                                                @error('ring_promotion_banner_title_3')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner description 3 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="ring_promotion_banner_desc_3" class="summernote">{{ old('ring_promotion_banner_desc_3',$obj['ring_promotion_banner_desc_3']) }}</textarea>
                                                @error('ring_promotion_banner_desc_3')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner link 3 </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_link_3" class="form-control" value="{{ old('ring_promotion_banner_link_3') }}">
                                                @error('ring_promotion_banner_link_3')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom4" class="col-xl-3 col-md-4">
                                            ring promotion banner last</label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="file" name="ring_promotion_banner_last" data-default-file="{{ env('AWS_URL') }}public/storage/{{ $obj->ring_promotion_banner_last }}"  class="form-control dropify">
                                                @error('ring_promotion_banner_last')
                                                <style> .dropify-wrapper { border:1px solid red;border-radius: 0.25rem; } </style>
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner alt </label>
                                            <div class="col-xl-8 col-md-7">
                                                <input type="text" name="ring_promotion_banner_alt" class="form-control" value="{{ old('ring_promotion_banner_alt',$obj['ring_promotion_banner_alt']) }}">
                                                @error('ring_promotion_banner_alt')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="validationCustom1" class="col-xl-3 col-md-4">ring promotion banner description </label>
                                            <div class="col-xl-8 col-md-7">
                                                <textarea name="ring_promotion_banner_desc" class="summernote">{{ old('ring_promotion_banner_desc',$obj['ring_promotion_banner_desc']) }}</textarea>
                                                @error('ring_promotion_banner_desc')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
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
