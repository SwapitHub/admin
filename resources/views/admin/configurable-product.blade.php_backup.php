@extends('layouts.layout')
@section('content')
    <style>
        .form-group-inner-main {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        input#validationCustom02 {
            width: 85%;
        }
    </style>
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="page-header-left">
                            <h3>Add Products
                                <small>Diamond Admin panel</small>
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
                            <li class="breadcrumb-item">Configurable</li>
                            <li class="breadcrumb-item active">Add Product</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->

        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <form action="{{ route('admin.product.postupdate', ['id' => $product['id']]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row product-adding">
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Product Type :
                                    {{ $product['type'] == 'parent_product' ? 'Base Product' : 'Child product' }}</h5>
                            </div>
                            <div class="card-body">
                                <div class="digital-add needs-validation">
                                    <div class="form-group">
                                        <label for="validationCustom01" class="col-form-label pt-0"><span>*</span>
                                            Product Type</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="parent_product"
                                                {{ $product['type'] == 'parent_product' ? 'selected' : '' }}>Parent Product
                                            </option>
                                            <option value="child_product"
                                                {{ $product['type'] == 'child_product' ? 'selected' : '' }}>Child Product
                                            </option>
                                        </select>
                                        {{-- <input class="form-control" name="type" id="validationCustom01" type="text"
                                            value="{{ old('type', $product['type']) }}" required=""> --}}
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom01" class="col-form-label pt-0"><span>*</span>
                                            Name</label>
                                        <input class="form-control" name="name" id="validationCustom01" type="text"
                                            value="{{ old('name', $product['name']) }}" required="">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom01" class="col-form-label pt-0">
                                            Product Browse PG Name</label>
                                        <input class="form-control" name="product_browse_pg_name" id="validationCustom01"
                                            type="text"
                                            value="{{ old('product_browse_pg_name', $product['product_browse_pg_name']) }}">
                                        @error('product_browse_pg_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustomtitle" class="col-form-label pt-0"><span>*</span>
                                            SKU</label>
                                        <input class="form-control" id="validationCustomtitle"
                                            value="{{ old('internal_sku', $product['sku']) }}" type="text"
                                            required="" readonly>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustomtitle" class="col-form-label pt-0"><span>*</span>
                                            Internal SKU</label>
                                        <input class="form-control" id="validationCustomtitle"
                                            value="{{ old('internal_sku', $product['internal_sku']) }}" type="text"
                                            required="" name="internal_sku">
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustomtitle" class="col-form-label pt-0"> Slug</label>
                                        <input class="form-control" id="validationCustomtitle" type="text"
                                            placeholder="{{ $product['slug'] }}">
                                        <small>Leave blank if you want system generated</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label categories-basic"><span>*</span>
                                            Menu</label>
                                        <select class="custom-select form-control" name="menu" id="product-menu"
                                            required="">
                                            <option selected disabled>--Select--</option>
                                            @foreach ($Menus as $menu)
                                                <option value="{{ $menu['id'] }}"
                                                    {{ $menu['id'] == $product['menu'] ? 'selected' : '' }}>
                                                    {{ $menu['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('menu')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label categories-basic"><span>*</span>
                                            Categories</label>
                                        <select class="custom-select form-control" name="category" id="product-category">
                                            <option selected disabled>--Select--</option>
                                            @if (!empty($categories))
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category['id'] }}"
                                                        {{ $category['id'] == $product['category'] ? 'selected' : '' }}>
                                                        {{ $category['name'] }}</option>
                                                @endforeach
                                            @endif;
                                        </select>
                                        @error('catagory')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label categories-basic"><span>*</span>
                                            Sub Categories</label>
                                        <select class="custom-select form-control" name="subcatagory"
                                            id="product-subcatagory">
                                            <option selected disabled>--Select--</option>
                                            @if (!empty($sub_categories))
                                                @foreach ($sub_categories as $sub_category)
                                                    <option value="{{ $sub_category['id'] }}"
                                                        {{ $sub_category['id'] == $product['sub_category'] ? 'selected' : '' }}>
                                                        {{ $sub_category['name'] }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('subcatagory')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label categories-basic"><span>*</span>
                                            Metal Type</label>
                                        <select class="custom-select form-control" name="metalType" required="">
                                            <option value="">--Select--</option>
                                            @foreach ($metalType as $metaltype)
                                                <option value="{{ $metaltype['id'] }}"
                                                    {{ $metaltype['id'] == $product['metalType_id'] ? 'selected' : '' }}>
                                                    {{ $metaltype['metal'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('metalType')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label categories-basic"><span>*</span>
                                            Metal Color</label>
                                        <select class="custom-select form-control" name="metalColor" required="">
                                            <option value="">--Select--</option>
                                            @foreach ($metalColor as $color)
                                                <option value="{{ $color['id'] }}"
                                                    {{ $color['id'] == $product['metalColor_id'] ? 'selected' : '' }}>
                                                    {{ $color['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('metalColor')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Metal Weight (in dwt)</label>
                                        <input class="form-control" id="validationCustom02" name="metalWeight"
                                            type="text" required=""
                                            value="{{ old('metalWeight', $product->metalWeight) }}">
                                        @error('metalWeight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Diamond Quality</label>
                                        <select name="diamond_quality" class="form-control">
                                            <option selected disabled>--Select--</option>
                                            <option value="SI1-SI2, G-H"
                                                {{ $product->diamondQuality == 'SI1-SI2, G-H' ? 'selected' : '' }}>SI1-SI2,
                                                G-H
                                            </option>
                                            <option value="SI1, G"
                                                {{ $product->diamondQuality == 'SI1, G' ? 'selected' : '' }}>SI1, G
                                            </option>
                                        </select>
                                        @error('diamond_quality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            SideDiamondNumber</label>
                                        <input class="form-control" id="validationCustom02" name="SideDiamondNumber"
                                            type="number" required=""
                                            value="{{ old('SideDiamondNumber', $product->SideDiamondNumber) }}">
                                        @error('SideDiamondNumber')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            No Of Gemstones</label>
                                        <input class="form-control" id="validationCustom02" name="NoOfGemstones1"
                                            type="text" required=""
                                            value="{{ old('NoOfGemstones1', $product->NoOfGemstones1) }}">
                                        @error('NoOfGemstones1')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span></span>
                                            Center stone</label>

                                        @if (!empty($product->center_stones))
                                            @php
                                                $center_stone = explode(',', $product->center_stones);
                                            @endphp
                                        @else
                                            @php
                                                $center_stone = [];
                                            @endphp
                                        @endif

                                        <select name="center_stone[]" id="center_stone"
                                            class="custom-select form-control multichoose" multiple>
                                            <option selected disabled>--select--</option>
                                            @foreach ($centerstones as $centerstone)
                                                <option value="{{ $centerstone['id'] }}"
                                                    {{ in_array($centerstone['id'], $center_stone) ? 'selected' : '' }}>
                                                    {{ $centerstone['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Center Shape</label>
                                        <!--input class="form-control" name="center_shape" id="validationCustom02" type="text" required="" value="{{ old('center_shape', $product->CenterShape) }}"-->
                                        <select name="center_shape" class="form-control">
                                            <option>--select--</option>
                                            <option value="TAKE PEG HEAD"
                                                <?= $product->CenterShape == 'TAKE PEG HEAD' ? 'selected' : '' ?>>TAKE PEG
                                                HEAD</option>
                                            @foreach ($centerShapes as $center_shape)
                                                <option value="{{ strtoupper($center_shape->shape) }}"
                                                    <?= strtoupper($center_shape->shape) == $product->CenterShape ? 'selected' : '' ?>>
                                                    {{ strtoupper($center_shape->shape) }}</option>
                                            @endforeach
                                        </select>
                                        @error('center_shape')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Finger Size </label>
                                        <select id="finger_size" name="finger_size" class="form-control"
                                            autocomplete="off">
                                            <option value="Stock" "selected="selected&quot;">Stock</option>
                                            <option value="3" {{ $product->FingerSize == '3' ? 'selected' : '' }}>3
                                            </option>
                                            <option value="3 1/4"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>3
                                                1/4</option>
                                            <option value="3 1/2"
                                                {{ $product->FingerSize == '3 1/2' ? 'selected' : '' }}>3
                                                1/2</option>
                                            <option value="3 3/4"
                                                {{ $product->FingerSize == '3 3/4' ? 'selected' : '' }}>3
                                                3/4</option>

                                            <option value="4" {{ $product->FingerSize == '4' ? 'selected' : '' }}>4
                                            </option>
                                            <option value="4 1/4"
                                                {{ $product->FingerSize == '4 1/4' ? 'selected' : '' }}>4
                                                1/4</option>
                                            <option value="4 1/2"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>4
                                                1/2</option>
                                            <option value="4 3/4"
                                                {{ $product->FingerSize == '4 3/4' ? 'selected' : '' }}>4
                                                3/4</option>

                                            <option value="5" {{ $product->FingerSize == '5' ? 'selected' : '' }}>5
                                            </option>
                                            <option value="5 1/4"
                                                {{ $product->FingerSize == '5 1/4' ? 'selected' : '' }}>5
                                                1/4</option>
                                            <option value="5 1/2"
                                                {{ $product->FingerSize == '5 1/2' ? 'selected' : '' }}>5
                                                1/2</option>
                                            <option value="5 3/4"
                                                {{ $product->FingerSize == '5 3/4' ? 'selected' : '' }}>5
                                                3/4</option>

                                            <option value="6" {{ $product->FingerSize == '6' ? 'selected' : '' }}>6
                                            </option>
                                            <option value="6 1/4"
                                                {{ $product->FingerSize == '6 1/4' ? 'selected' : '' }}>6
                                                1/4</option>
                                            <option value="6 1/2"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>6
                                                1/2</option>
                                            <option value="6 3/4"
                                                {{ $product->FingerSize == '6 3/4' ? 'selected' : '' }}>6
                                                3/4</option>

                                            <option value="7" {{ $product->FingerSize == '7' ? 'selected' : '' }}>7
                                            </option>
                                            <option value="7 1/4"
                                                {{ $product->FingerSize == '7 1/4' ? 'selected' : '' }}>7
                                                1/4</option>
                                            <option value="7 1/2"
                                                {{ $product->FingerSize == '7 1/2' ? 'selected' : '' }}>7
                                                1/2</option>
                                            <option value="7 3/4"
                                                {{ $product->FingerSize == '7 3/4' ? 'selected' : '' }}>7
                                                3/4</option>

                                            <option value="8" {{ $product->FingerSize == '8' ? 'selected' : '' }}>8
                                            </option>
                                            <option value="8 1/4"
                                                {{ $product->FingerSize == '8 1/4' ? 'selected' : '' }}>8
                                                1/4</option>
                                            <option value="8 1/2"
                                                {{ $product->FingerSize == '8 1/2' ? 'selected' : '' }}>8
                                                1/2</option>
                                            <option value="8 3/4"
                                                {{ $product->FingerSize == '8 3/4' ? 'selected' : '' }}>8
                                                3/4</option>

                                            <option value="9" {{ $product->FingerSize == '9' ? 'selected' : '' }}>9
                                            </option>
                                            <option value="9 1/4"
                                                {{ $product->FingerSize == '9 1/2' ? 'selected' : '' }}>9
                                                1/4</option>
                                            <option value="9 1/2"
                                                {{ $product->FingerSize == '9 1/2' ? 'selected' : '' }}>9
                                                1/2</option>
                                            <option value="9 3/4" {{ $product->FingerSize == '10' ? 'selected' : '' }}>9
                                                3/4</option>

                                            <option value="10" {{ $product->FingerSize == '10' ? 'selected' : '' }}>10
                                            </option>
                                            <option value="10 1/4"
                                                {{ $product->FingerSize == '10 3/4' ? 'selected' : '' }}>
                                                10 1/4</option>
                                            <option value="10 1/2"
                                                {{ $product->FingerSize == '10 3/4' ? 'selected' : '' }}>
                                                10 1/2</option>
                                            <option value="10 3/4"
                                                {{ $product->FingerSize == '10 3/4' ? 'selected' : '' }}>
                                                10 3/4</option>

                                            <option value="11"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>
                                                11</option>
                                            <option value="11 1/4"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>
                                                11 1/4</option>
                                            <option value="11 1/2"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>
                                                11 1/2</option>
                                            <option value="11 3/4"
                                                {{ $product->FingerSize == '3 1/4' ? 'selected' : '' }}>
                                                11 3/4</option>

                                            <option value="12" {{ $product->FingerSize == '12' ? 'selected' : '' }}>12
                                            </option>
                                            <option value="12 1/4"
                                                {{ $product->FingerSize == '12 3/4' ? 'selected' : '' }}>
                                                12 1/4</option>
                                            <option value="12 1/2"
                                                {{ $product->FingerSize == '12 3/4' ? 'selected' : '' }}>
                                                12 1/2</option>
                                            <option value="12 3/4"
                                                {{ $product->FingerSize == '12 3/4' ? 'selected' : '' }}>
                                                12 3/4</option>

                                            <option value="13" {{ $product->FingerSize == '13' ? 'selected' : '' }}>13
                                            </option>
                                            <option value="13 1/4"
                                                {{ $product->FingerSize == '13 1/4' ? 'selected' : '' }}>
                                                13 1/4</option>
                                            <option value="13 1/2"
                                                {{ $product->FingerSize == '13 1/2' ? 'selected' : '' }}>
                                                13 1/2</option>
                                            <option value="13 3/4" {{ $product->FingerSize == '14' ? 'selected' : '' }}>13
                                                3/4</option>

                                            <option value="14" {{ $product->FingerSize == '14' ? 'selected' : '' }}>14
                                            </option>
                                            <option value="14 1/4"
                                                {{ $product->FingerSize == '14 1/4' ? 'selected' : '' }}>
                                                14 1/4</option>
                                            <option value="14 1/2"
                                                {{ $product->FingerSize == '14 1/2' ? 'selected' : '' }}>
                                                14 1/2</option>
                                            <option value="14 3/4"
                                                {{ $product->FingerSize == '14 3/4' ? 'selected' : '' }}>
                                                14 3/4</option>

                                            <option value="15" {{ $product->FingerSize == '15' ? 'selected' : '' }}>15
                                            </option>
                                            <option value="15 1/4"
                                                {{ $product->FingerSize == '15 1/4' ? 'selected' : '' }}>
                                                15 1/4</option>
                                            <option value="15 1/2"
                                                {{ $product->FingerSize == '15 1/2' ? 'selected' : '' }}>
                                                15 1/2</option>
                                            <option value="15 3/4"
                                                {{ $product->FingerSize == '15 3/4' ? 'selected' : '' }}>
                                                15 3/4</option>


                                        </select>
                                        @error('finger_size')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Default Image</label>
                                        <input class="form-control" id="validationCustom02" type="file"
                                            name="default_image_url">
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            Default Image alt</label>
                                        <input class="form-control" id="validationCustom02" type="text"
                                            name="default_image_alt"
                                            value="{{ old('default_image_alt', $product->default_image_alt) }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label"><span>*</span>
                                            shippingDay</label>
                                        <input class="form-control" name="shippingDay" id="validationCustom02"
                                            type="number" required=""
                                            value="{{ old('shippingDay', $product->shippingDay) }}">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label"><span>*</span> Product Mark As</label>
                                        <div class="m-checkbox-inline mb-0 custom-radio-ml d-flex radio-animated">
                                            <div class="checkbox checkbox-primary">
                                                <input id="is_newest" type="checkbox" data-original-title=""
                                                    {{ $product->is_newest == '1' ? 'checked' : '' }} name="is_newest"
                                                    value="1" title="">
                                                <label for="is_newest">Newest</label>
                                            </div>
                                            <div class="checkbox checkbox-primary">
                                                <input id="is_bestseller" type="checkbox" data-original-title=""
                                                    {{ $product->is_bestseller == '1' ? 'checked' : '' }}
                                                    name="is_bestseller" value="1" title="">
                                                <label for="is_bestseller">Best seller</label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label"><span>*</span> Status</label>
                                        <div class="m-checkbox-inline mb-0 custom-radio-ml d-flex radio-animated">
                                            <label class="d-block" for="edo-ani">
                                                <input class="radio_animated" id="edo-ani" type="radio"
                                                    name="status" value="true"
                                                    {{ $product->status == 'true' ? 'checked' : '' }}>
                                                Enable
                                            </label>
                                            <label class="d-block" for="edo-ani1">
                                                <input class="radio_animated" id="edo-ani1" type="radio"
                                                    name="status" value="false"
                                                    {{ $product->status == 'false' ? 'checked' : '' }}>
                                                Disable
                                            </label>
                                        </div>
                                    </div>

                                    <!--meta data--->
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label">
                                            Meta Title</label>
                                        <input class="form-control" name="meta_title" id="validationCustom02"
                                            type="text"
                                            value="{{ !empty($product->meta_title) ? $product->meta_title : $product->name }}"
                                            placeholder="Meta Title">
                                    </div>
                                    {{-- <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label">
                                            Meta Keyword</label>
                                        <input class="form-control" name="meta_keyword" id="validationCustom02"
                                            type="text" value="{{ $product->meta_keyword }}"
                                            placeholder="Meta Keyword">
                                    </div> --}}
                                    <div class="form-group">
                                        <label for="validationCustom02" class="col-form-label">
                                            Meta Description</label>
                                        <input class="form-control" name="meta_description" id="validationCustom02"
                                            type="text"
                                            value="{{ !empty($product->meta_description) ? $product->meta_description : $product->description }}"
                                            placeholder="Meta Description">
                                    </div>
                                    <!--meta data--->




                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Add Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="digital-add needs-validation">
                                        <div class="form-group mb-0">
                                            <div class="description-sm">
                                                <textarea name="description" cols="10" rows="4">{{ $product->description }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label pt-0"> Product Image</label>
                                    <div class="dropzone digits" id="singleFileUpload" style="padding:0;">
                                        @if (!empty($product->images))
                                            @php
                                                $product_images = json_decode($product->images);
                                            @endphp
                                            @foreach ($product_images as $pro_img)
                                                <div
                                                    class="dz-preview dz-processing dz-image-preview dz-error dz-complete">
                                                    <div class="dz-image"><img data-dz-thumbnail=""
                                                            alt="pngtree-vector-user-young-boy-avatar-icon-png-image_1538408.jpg"
                                                            src="{{ $pro_img }}" style="height:121px;width:121px">
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif




                                        {{-- <div class="dz-message needsclick"><i class="fa fa-cloud-upload"></i>
												<h4 class="mb-0 f-w-600">Drop files here or click to upload.</h4>
											</div> --}}
                                    </div>
                                </div>
                                @php
                                    $vid = json_decode($product->videos);
                                @endphp
                                <div class="form-group">
                                    <label for="validationCustom02" class="col-form-label"><span>*</span>
                                        Product Video (white) </label>
                                    <div class="form-group-inner-main">
                                        <input class="form-control" id="validationCustom02" name="product_video_white"
                                            type="file">
                                        <button class="btn btn-primary"
                                            onclick="showVideo('{{ !empty($vid->white) && isset($vid) ? $vid->white : 'NA' }}')"
                                            title="View Video" type="button" style="padding: 11px 16px"><i
                                                class="fa fa-eye"></i></button>
                                    </div>
                                    <span>{{ !empty($vid->white) && isset($vid) ? $vid->white : 'NA' }}</span>
                                    @error('product_video_white')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                </div>
                                <div class="form-group">
                                    <label for="validationCustom02" class="col-form-label"><span>*</span>
                                        Product Video (yellow) </label>
                                    <div class="form-group-inner-main">
                                        <input class="form-control" id="validationCustom02" name="product_video_yellow"
                                            type="file">
                                        <button class="btn btn-primary" title="View Video" type="button"
                                            onclick="showVideo('{{ !empty($vid->yellow) && isset($vid) ? $vid->yellow : 'NA' }}')"
                                            style="padding: 11px 16px"><i class="fa fa-eye"></i></button>
                                    </div>
                                    @error('product_video_yellow')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span>{{ !empty($vid->yellow) && isset($vid) ? $vid->yellow : 'NA' }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="validationCustom02" class="col-form-label"><span>*</span>
                                        Product Video (rose) </label>
                                    <div class="form-group-inner-main">
                                        <input class="form-control" id="validationCustom02" name="product_video_rose"
                                            type="file">
                                        <button class="btn btn-primary" title="View Video" type="button"
                                            onclick="showVideo('{{ !empty($vid->rose) && isset($vid) ? $vid->rose : 'NA' }}')"
                                            style="padding: 11px 16px"><i class="fa fa-eye"></i></button>
                                    </div>
                                    @error('product_video_rose')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <span>{{ !empty($vid->rose) && isset($vid) ? $vid->rose : 'NA' }}</span>
                                </div>

                            </div>
                        </div>
                        @if (!empty($variations))
                            <div class="card">
                                <div class="card-header" style="display:block">
                                    <h5>Variations (Child products)</h5>
                                    <p> Variation products are depend on all possible combination of attribute</p>

                                    <button type="button" data-bs-toggle="modal"
                                        data-bs-target="#addVariantProductModal" class="btn btn-success">Add
                                        Variant</button>
                                </div>
                                <div class="card-body">
                                    <div class="user-status table-responsive products-table">
                                        <table class="table table-bordernone mb-0">
                                            <thead>
                                                <tr>
                                                    <th style="min-width: 10px !important;"><span type="button"
                                                            class="badge badge-primary add-row delete_all"><i
                                                                class="fa fa-trash"></i></span></th>
                                                    <th scope="col">Details</th>
                                                    <th scope="col">Price</th>
                                                    <th scope="col">action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><input type="hidden" value="{{ url('db-product-list/delete') }}"
                                                        name="url" id="url"></tr>
                                                @foreach ($variations as $variation)
                                                    <tr data-row-id="{{ $variation['id'] }}">
                                                        <td style="min-width: 10px !important;">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="" id="flexCheckDefault"
                                                                data-id="{{ $variation['id'] }}">
                                                        </td>
                                                        <td>{{ $variation['sku'] }}</td>
                                                        <td class="digits"><a href="#"><i class="fa fa-eye"
                                                                    title="view Price"><i></i></td>
                                                        <td class="action">
                                                            <a
                                                                href="{{ route('admin.product.edit', ['id' => $variation['id']]) }}"><i
                                                                    class="fa fa-edit"></i></a>
                                                            {{-- <a href="javascript:void(0)" onclick="deleteItem('{{ url('db-product-list/delete/') }}/{{ $variation['id'] }}')"><i class="fa fa-trash"></i></a> --}}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <h5 class="col-form-label">Choose Similar Products</h5>
                                <button type="button" class="btn btn-small btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addSimilarProductModal">Add Product</button>
                            </div>
                            <div class="card-body" id="similar-table">

                                @if (empty($product['similar_products']))
                                    <div class="digital-add needs-validation">
                                        <div class="placeholder-img">
                                            <center>
                                                <img src="https://s3-sama.s3.us-east-2.amazonaws.com/public/icon-add-product.svg"
                                                    style="height:80px;width:80px"
                                                    class="height=80px; weight=80px dark:invert dark:mix-blend-exclusion"><br>
                                                <span class="col-form-label">add similar product</span>
                                            </center>
                                        </div>
                                    </div>
                                @else
                                    <table class="table table-responsive table-stripped">
                                        <thead>
                                            <tr>
                                                <th>sr</th>
                                                <th>name</th>
                                                <th>sku</th>
                                                <th>action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($similar_products as $index => $items)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $items['name'] }}</td>
                                                    <td>{{ $items['sku'] }}</td>
                                                    <td><i class="fa fa-trash delete-similar-product"
                                                            data-product-id="{{ $items['id'] }}"></i></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif


                            </div>


                        </div>

                    </div>
                    <div class="digital-add needs-validation mb-3" style="display: flex;flex-direction: row-reverse;">
                        <div class="form-group mb-0">
                            <div class="product-buttons">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-dark">Discard</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <!-- Container-fluid Ends-->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="addSimilarProductModal" tabindex="-1" aria-labelledby="addSimilarProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.product.similar') }}" method="POST" id="submit-smilar">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSimilarProductModalLabel">Choose Similar Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="col-form-label"><span class="text-danger">*</span> Manu</label>
                            <select class="custom-select form-control" name="similar-menu" required=""
                                id="similar-menu">
                                <option value="">--Select--</option>
                                @foreach ($Menus as $pmenu)
                                    <option value="{{ $pmenu['id'] }}">{{ $pmenu['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="id" value="{{ $product['id'] }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label"><span class="text-danger">*</span>
                                categories</label>
                            <select class="custom-select form-control" name="similar-category" required=""
                                id="similar-category">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Subcategory</label>
                            <select class="custom-select form-control" name="similar-subcategory"
                                id="similar-subcategory">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                        <div class="form-group" id="multichoice">
                            <label for="" class="col-form-label"><span class="text-danger">*</span>
                                Products</label>
                            <select class="custom-select form-control multichoose" multiple name="similar-product[]"
                                id="" required="">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Video Modal -->
    <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="videoModalLabel">Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body" id="videoModal">
                    <i class="fa fa-spinner fa-2x fa-spin" id="vid-spiner"></i>
                    <iframe width="100%" id="vid" height="345" src="">
                    </iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Variant Modal -->
    <div class="modal fade" id="addVariantProductModal" tabindex="-1" aria-labelledby="addVariantProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="#" method="POST" id="submit-variant">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSimilarProductModalLabel">Choose Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="col-form-label"><span class="text-danger">*</span> Manu</label>
                            <select class="custom-select form-control" name="variant-menu" required=""
                                id="variant-menu">
                                <option value="">--Select--</option>
                                @foreach ($Menus as $pmenu)
                                    <option value="{{ $pmenu['id'] }}">{{ $pmenu['name'] }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="baseproduct_id" value="{{ $product['id'] }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label"><span class="text-danger">*</span>
                                categories</label>
                            <select class="custom-select form-control" name="variant-category" required=""
                                id="variant-category">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-form-label">Subcategory</label>
                            <select class="custom-select form-control" name="variant-subcategory"
                                id="variant-subcategory">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                        <div class="form-group" id="multichoice-pro">
                            <label for="" class="col-form-label"><span class="text-danger">*</span>
                                Products</label>
                            <select class="custom-select form-control multichoose" multiple name="variant-product[]"
                                id="" required="">
                                <option value="">--Select--</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            $("#similar-menu").change(function() {
                var menuid = $("#similar-menu").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-category/' + menuid;
                $("#similar-category").load(url);
            });

            $("#similar-category").change(function() {
                var menuid = $("#similar-menu").val();
                var catid = $("#similar-category").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-subcategory/' + menuid + "/" + catid;
                $("#similar-subcategory").load(url);
                var sub_category_id = ''
                getProductBasedOnValues(menuid, catid, sub_category_id);
            });
            $("#similar-subcategory").change(function() {
                var menuid = $("#similar-menu").val();
                var catid = $("#similar-category").val();
                var subcategory = $("#similar-subcategory").val();
                getProductBasedOnValues(menuid, catid, subcategory);
            });

            function getProductBasedOnValues(menu_id, category_id, sub_category_id) {
                var product_id = {{ $product->id }};
                category_id = category_id || '';
                sub_category_id = sub_category_id || '';
                var jsondata = {
                    product_id: product_id,
                    menu_id: menu_id,
                    category_id: category_id,
                    sub_category_id: sub_category_id
                };
                var csrfToken = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{ url('filter-product') }}",
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        data: jsondata
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(res) {
                        // console.log(res);
                        $("#multichoice").html(res);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            }

            $("#submit-smilar").submit(function(event) {
                event.preventDefault();
                var formData = new FormData($("#submit-smilar")[0]);
                var csrfToken = '{{ csrf_token() }}';
                // console.log(formData);
                $.ajax({
                    url: "{{ route('admin.product.similar') }}",
                    method: "POST",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false, // Important when using FormData
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(res) {
                        console.log(res.res);
                        if (res.res == 'success') {
                            $("#similar-table").html(res.table);
                            $('#submit-smilar')[0].reset();
                            $("#addSimilarProductModal").modal('hide');
                        }
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            })


            // remove similar product from product

            $(document).on('click', '.delete-similar-product', function() {
                var similarProductId = $(this).data('product-id');
                var productId = {{ $product->id }};
                var csrfToken = '{{ csrf_token() }}';

                var $closestTr = $(this).closest('tr');
                console.log('$closestTr:', $closestTr);

                //Confirm deletion
                if (confirm('Are you sure you want to delete this product?')) {
                    $.ajax({
                        url: "{{ route('admin.remove.similar') }}",
                        method: "POST",
                        data: {
                            product_id: productId,
                            similar_id: similarProductId
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            console.log(response);

                            if (response.success) {
                                if ($closestTr.length > 0) {
                                    $closestTr.remove();
                                    console.log('Product row removed successfully.');
                                } else {
                                    console.log('Unable to find closest <tr> element.');
                                }
                            } else {
                                alert('Failed to delete product.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                }
            });

            function showVideo(video) {
                if (video != 'NA') {
                    $("#videoModal").modal('show');
                    $("#vid").attr('src', video);
                    $("#vid-spiner").hide();
                }
            }
            // added new script for change manu category and subcategory
            $("#product-menu").change(function() {
                var menuid = $("#product-menu").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-category/' + menuid;
                $("#product-category").load(url);
            });

            $("#product-category").change(function() {
                var menuid = $("#product-menu").val();
                var catid = $("#product-category").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-subcategory/' + menuid + "/" + catid;
                $("#product-subcatagory").load(url);
                var sub_category_id = ''
                getProductBasedOnValues(menuid, catid, sub_category_id);
            });


            // show product for variant
            $("#variant-menu").change(function() {
                var menuid = $("#variant-menu").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-category/' + menuid;
                $("#variant-category").load(url);
            });

            $("#variant-category").change(function() {
                var menuid = $("#variant-menu").val();
                var catid = $("#variant-category").val();
                var baseUrl = "{{ url('/') }}";
                var url = baseUrl + '/filter-subcategory/' + menuid + "/" + catid;
                $("#variant-subcategory").load(url);
                var sub_category_id = ''
                getProductBasedOnValues(menuid, catid, sub_category_id);
            });
            $("#variant-subcategory").change(function() {
                var menuid = $("#variant-menu").val();
                var catid = $("#variant-category").val();
                var subcategory = $("#variant-subcategory").val();
                getProductBasedOnValues(menuid, catid, subcategory);
            });

            function getProductBasedOnValues(menu_id, category_id, sub_category_id) {
                var product_id = {{ $product->id }};
                category_id = category_id || '';
                sub_category_id = sub_category_id || '';
                var jsondata = {
                    product_id: product_id,
                    menu_id: menu_id,
                    category_id: category_id,
                    sub_category_id: sub_category_id
                };
                var csrfToken = '{{ csrf_token() }}';
                $.ajax({
                    url: "{{ url('filter-product') }}",
                    method: "POST",
                    data: {
                        _token: csrfToken,
                        data: jsondata
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(res) {
                        // console.log(res);
                        $("#multichoice-pro").html(res);
                    },
                    error: function(res) {
                        console.log(res);
                    }
                });
            }
        </script>
    @endpush
@endsection
