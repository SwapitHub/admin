<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use Illuminate\Support\Facades\DB;

class ProductCategoryController extends Controller
{

    ## main category list
    public function index()
    {
        $data = [
            "title" => 'Main Category list',
            "viewurl" => 'admin.create.productcat',
            "editurl" => 'admin.edit.productcat',
            'list' => ProductCategory::orderBy('id', 'desc')->get(),
        ];
        return view('admin.main_categorylist', $data);
    }

    public function deleteCategory($id)
    {
        if ($id) {
            $obj = ProductCategory::find($id);
            $obj->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Category Id Required';
        }
        echo json_encode($output);
    }

    public function createProductCategory()
    {
        $data = [
            'url_action' => route('admin.create.productcat'),
            'backtrack' => 'admin.catlist',
            'title' => 'Add Product Category',
            "obj" => '',
        ];
        return view('admin.product_category', $data);
    }

    public function postCreateProductCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'The Name field is required.',
        ]);
        $Productcat = new ProductCategory;
        $Productcat->name = $request->name;
        $uniqueslug = $request->slug ?? $request->name;
        $slug = $Productcat->generateUniqueSlug($uniqueslug);
        $Productcat->slug = $slug;
        // $prong->order_number = $request->order_number;
        // $prong->icon = $imagepath;
        $Productcat->status = $request->status ?? 'false';
        $Productcat->save();
        return redirect()->back()->with('success', 'Product category added successfully');
    }

    public function postEditProCategory(Request $request, $id)
    {
        $obj = ProductCategory::find($id);
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'The name field is required.',
        ]);

        if ($request->slug) {
            $slug = $obj->generateUniqueSlug($request->slug);
        } else {
            $slug = $obj->slug;
        }

        $obj->name = $request->name;
        $obj->slug = $slug;
        // $obj->order_number = $request->order_number;
        // $obj->icon = $imagepath;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'Product category updated successfully');
    }


    public function editProductCategory($id)
    {
        $editdata = ProductCategory::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('admin.update.procat', ['id' => $editdata['id']]),
            'backtrack' => 'admin.catlist',
            'title' => 'Edit Product Category',
            'obj' => $editdata,
        ];
        return view('admin.product_category', $data);
    }











    ## subcategory list
    public function subcatList()
    {

        $subcat = DB::table('product_subcategory')
            ->join('product_category', 'product_subcategory.category_id', '=', 'product_category.id')
            ->select('product_subcategory.*', 'product_category.name as category_name')
            ->orderBy('product_subcategory.id', 'desc')
            ->get();
        $data = [
            "title" => 'Subcategory list',
            "viewurl" => 'admin.create.productsubcat',
            "editurl" => 'admin.edit.productsubcat',
            'list' => $subcat,
        ];
        return view('admin.product_subcategorylist', $data);
    }

    public function deleteSubcategory($id)
    {
        if ($id) {
            $obj = ProductSubcategory::find($id);
            $obj->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Subcategory Id Required';
        }
        echo json_encode($output);
    }

    public function createProductSubcategory()
    {
        $data = [
            'url_action' => route('admin.create.productsubcat'),
            'backtrack' => 'admin.subcatlist',
            'title' => 'Add Product Subcategory',
            'categories' => ProductCategory::orderBy('id', 'desc')->where('status','true')->get(),
            // "obj" => '',
        ];
        return view('admin.product_subcategory', $data);
    }

    public function postCreateProductSubcategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
        ], [
            'name.required' => 'The Name field is required.',
            'category_id.required' => 'The category field is required.',
        ]);
        $productscat = new Productsubcategory;
        $productscat->name = $request->name;
        $productscat->category_id = $request->category_id;
        $uniqueslug = $request->slug ?? $request->name;
        $slug = $productscat->generateUniqueSlug($uniqueslug);
        $productscat->slug = $slug;
        // $prong->order_number = $request->order_number;
        // $prong->icon = $imagepath;
        $productscat->status = $request->status ?? 'false';
        $productscat->save();
        return redirect()->back()->with('success', 'Product subcategory added successfully');
    }

    public function postEditProSubcategory(Request $request, $id)
    {
        $obj = Productsubcategory::find($id);
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
        ], [
            'name.required' => 'The name field is required.',
            'category_id.required' => 'The category field is required.',
        ]);

        if ($request->slug) {
            $slug = $obj->generateUniqueSlug($request->slug);
        } else {
            $slug = $obj->slug;
        }

        $obj->name = $request->name;
        $obj->category_id = $request->category_id;
        $obj->slug = $slug;
        // $obj->order_number = $request->order_number;
        // $obj->icon = $imagepath;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'Product category updated successfully');
    }


    public function editProductSubcategory($id)
    {
        $editdata = Productsubcategory::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('admin.update.prosubcat', ['id' => $editdata['id']]),
            'backtrack' => 'admin.subcatlist',
            'title' => 'Edit Product Subategory',
            'categories' => ProductCategory::orderBy('id', 'desc')->where('status','true')->get(),
            'obj' => $editdata,
        ];
        return view('admin.product_subcategory', $data);
    }

}
