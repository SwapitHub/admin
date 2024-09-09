<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\HomeContent;
use App\Models\Banner;
use App\Models\HomeSection1;
use App\Models\HomeSection2;
use App\Models\HomeSection3;
use App\Models\HomeSection4;
use App\Models\HomeSection5;
use App\Models\HomeSection6;
use App\Models\ShopByCategoryHomePage;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;

class HomeContentController extends Controller
{
    public function index()
    {
        $sectionData = [
            'section1' => HomeSection1::first(),
            'section2' => HomeSection2::first(),
            'section3' => HomeSection3::first(),
            'section4' => HomeSection4::first(),
            'section5' => HomeSection5::first(),
            'section6' => HomeSection6::first(),
        ];
        $banner = Banner::orderBy('id', 'desc')->where('type', 'Home')->get();
        $data = [
            "title" => 'Home Content',
            "url_action" => '',
            'bannerlist' => $banner,
            'sectionList' => $sectionData,
            "obj" => HomeContent::find(1)
        ];
        return view('admin.homecontant', $data);
    }

    public function section1(Request $request)
    {
        $homecontant =  HomeSection1::find(1);
        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
            'btn_name' => 'required',
            'link' => 'required',
        ], [
            'heading.required' => 'The heading field is required.',
            'description.required' => 'The description field is required.',
            'btn_name.required' => 'The button name field is required.',
            'link.required' => 'The button link field is required.',
        ]);
        if ($request->file('image') != NULL) {
            $oldImagePath = $homecontant->image; // Replace with the actual path
            if ($oldImagePath) {
                $oldImagePath = 'public/' . $oldImagePath;
                Storage::disk('s3')->delete($oldImagePath);
            }
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = "section1_" . time() . '.' . $extension;
            $path = $request->file('image')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path, 'public');
            $bannerpath = 'images/home/' . $fileName;
        } else {
            $bannerpath = $homecontant->image;
        }

        $conditions = ['id' => 1];
        $values = [
            'heading' => $request->heading,
            'description' => $request->description,
            'btn_name' => $request->btn_name,
            'link' => $request->link,
            'image' => $bannerpath,
            'image_alt' => $request->image_alt,
            'status' => $request->status ?? 'false',
        ];
        HomeSection1::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function section2(Request $request)
    {
        $homecontant =  HomeSection2::find(1);
        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
            'btn_name' => 'required',
            'link' => 'required',
        ], [
            'heading.required' => 'The heading field is required.',
            'description.required' => 'The description field is required.',
            'btn_name.required' => 'The button name field is required.',
            'link.required' => 'The button link field is required.',
        ]);
        if ($request->file('image') != NULL) {

            if (!is_null($homecontant)) {
                $oldImagePath = $homecontant->image;
                if ($oldImagePath) {
                    $oldImagePath = 'public/' . $oldImagePath;
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = "section2_" . time() . '.' . $extension;
            $path = $request->file('image')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path, 'public');
            $bannerpath = 'images/home/' . $fileName;
        } else {
            $bannerpath = $homecontant->image;
        }
        $conditions = ['id' => 1];
        $values = [
            'heading' => $request->heading,
            'description' => $request->description,
            'btn_name' => $request->btn_name,
            'link' => $request->link,
            'image' => $bannerpath,
            'image_alt' => $request->image_alt,
            'status' => $request->status ?? 'false',
        ];
        HomeSection2::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function section3(Request $request)
    {
        $homecontant =  HomeSection3::find(1);
        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
            'btn_name' => 'required',
            'link' => 'required',
        ], [
            'heading.required' => 'The heading field is required.',
            'description.required' => 'The description field is required.',
            'btn_name.required' => 'The button name field is required.',
            'link.required' => 'The button link field is required.',
        ]);
        if ($request->file('image') != NULL) {

            if (!is_null($homecontant)) {
                $oldImagePath = $homecontant->image;
                if ($oldImagePath) {
                    $oldImagePath = 'public/' . $oldImagePath;
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $extension = $request->file('image')->getClientOriginalExtension();
            $fileName = "section2_" . time() . '.' . $extension;
            $path = $request->file('image')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path, 'public');
            $bannerpath = 'images/home/' . $fileName;
        } else {
            $bannerpath = $homecontant->image;
        }
        $conditions = ['id' => 1];
        $values = [
            'heading' => $request->heading,
            'description' => $request->description,
            'btn_name' => $request->btn_name,
            'link' => $request->link,
            'image' => $bannerpath,
            'image_alt' => $request->image_alt,
            'status' => $request->status ?? 'false',
        ];
        HomeSection3::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function section4(Request $request)
    {
        $homecontant =  HomeSection4::find(1);
        $this->validate($request, [
            'heading' => 'required',
            'description' => 'required',
            'btn_name' => 'required',
            'link' => 'required',
        ], [
            'heading.required' => 'The heading field is required.',
            'description.required' => 'The description field is required.',
            'btn_name.required' => 'The button name field is required.',
            'link.required' => 'The button link field is required.',
        ]);


        if ($request->file('image1') != NULL) {
            if (!is_null($homecontant)) {

                if ($homecontant->image1) {
                    $oldImagePath = 'public/' . $homecontant->image1;
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $extension = $request->file('image1')->getClientOriginalExtension();
            $fileName = "section4_" . time() . '.' . $extension;
            $path1 = $request->file('image1')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path1, 'public');
            $bannerpath1 = 'images/home/' . $fileName;
        } else {
            $bannerpath1 = $homecontant->image1;
        }

        // second image
        if ($request->file('image2') != NULL) {

            if (!is_null($homecontant)) {
                if ($homecontant->image2) {
                    $oldImage2Path = 'public/' . $homecontant->image2;
                    Storage::disk('s3')->delete($oldImage2Path);
                }
            }

            $extension = $request->file('image2')->getClientOriginalExtension();
            $fileName = "section4_" . time() . '.' . $extension;
            $path2 = $request->file('image2')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path2, 'public');
            $bannerpath2 = 'images/home/' . $fileName;
        } else {
            $bannerpath2 = $homecontant->image2;
        }


        $conditions = ['id' => 1];
        $values = [
            'heading' => $request->heading,
            'description' => $request->description,
            'btn_name' => $request->btn_name,
            'link' => $request->link,
            'image1' => $bannerpath1,
            'image1_alt' => $request->image1_alt,
            'image2' => $bannerpath2,
            'image2_alt' => $request->image2_alt,
            'status' => $request->status ?? 'false',
        ];
        HomeSection4::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function section5(Request $request)
    {
        $homecontant =  HomeSection5::find(1);
        $this->validate($request, [
            'heading' => 'required',
            'subheading' => 'required',
            'description' => 'required',
            'btn_name' => 'required',
            'link' => 'required',
        ], [
            'heading.required' => 'The heading field is required.',
            'subheading.required' => 'The subheading field is required.',
            'description.required' => 'The description field is required.',
            'btn_name.required' => 'The button name field is required.',
            'link.required' => 'The button link field is required.',
        ]);


        if ($request->file('image_desktop') != NULL) {
            if (!is_null($homecontant)) {

                if ($homecontant->image_desktop) {
                    $oldImagePath = 'public/' . $homecontant->image_desktop;
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $extension = $request->file('image_desktop')->getClientOriginalExtension();
            $fileName = "section5_desktop_" . time() . '.' . $extension;
            $path1 = $request->file('image_desktop')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path1, 'public');
            $bannerpath1 = 'images/home/' . $fileName;
        } else {
            $bannerpath1 = $homecontant->image_desktop;
        }

        // second image
        if ($request->file('image_mobile') != NULL) {

            if (!is_null($homecontant)) {
                if ($homecontant->image_mobile) {
                    $oldImage2Path = 'public/' . $homecontant->image_mobile;
                    Storage::disk('s3')->delete($oldImage2Path);
                }
            }

            $extension = $request->file('image_mobile')->getClientOriginalExtension();
            $fileName = "section5_mobile_" . time() . '.' . $extension;
            $path2 = $request->file('image_mobile')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path2, 'public');
            $bannerpath2 = 'images/home/' . $fileName;
        } else {
            $bannerpath2 = $homecontant->image_mobile;
        }


        $conditions = ['id' => 1];
        $values = [
            'heading' => $request->heading,
            'subheading' => $request->subheading,
            'description' => $request->description,
            'btn_name' => $request->btn_name,
            'link' => $request->link,
            'image_desktop' => $bannerpath1,
            'image_desktop_alt' => $request->image_desktop_alt,
            'image_mobile' => $bannerpath2,
            'image_mobile_alt' => $request->image_mobile_alt,
            'status' => $request->status ?? 'false',
        ];
        HomeSection5::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function section6(Request $request)
    {
        $homecontant =  HomeSection6::find(1);
        $this->validate($request, [
            'heading1' => 'required',
            'heading2' => 'required',
            'description1' => 'required',
            'description2' => 'required',
            'btn_name' => 'required',
            'btn_name2' => 'required',
            'btn_link' => 'required',
            'btn_link2' => 'required',
        ], [
            'heading1.required' => 'The ourstory heading field is required.',
            'heading2.required' => 'The mission heading  field is required.',
            'description1.required' => 'The description1 field is required.',
            'description2.required' => 'The description2 field is required.',
            'btn_name.required' => 'The button name field is required.',
            'btn_name2.required' => 'The button name2 field is required.',
            'btn_link.required' => 'The button link field is required.',
            'btn_link2.required' => 'The button link2 field is required.',
        ]);


        if ($request->file('image1') != NULL) {
            if (!is_null($homecontant)) {

                if ($homecontant->image1) {
                    $oldImagePath = 'public/' . $homecontant->image1;
                    Storage::disk('s3')->delete($oldImagePath);
                }
            }

            $extension = $request->file('image1')->getClientOriginalExtension();
            $fileName = "section6_ourstory_" . time() . '.' . $extension;
            $path1 = $request->file('image')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path1, 'public');
            $bannerpath1 = 'images/home/' . $fileName;
        } else {
            $bannerpath1 = $homecontant->image;
        }

        // second image
        if ($request->file('image2') != NULL) {

            if (!is_null($homecontant)) {
                if ($homecontant->image2) {
                    $oldImage2Path = 'public/' . $homecontant->image2;
                    Storage::disk('s3')->delete($oldImage2Path);
                }
            }

            $extension = $request->file('image2')->getClientOriginalExtension();
            $fileName = "section6_mission_" . time() . '.' . $extension;
            $path2 = $request->file('image2')->storeAs('public/images/home', $fileName, 's3');
            Storage::disk('s3')->setVisibility($path2, 'public');
            $bannerpath2 = 'images/home/' . $fileName;
        } else {
            $bannerpath2 = $homecontant->image2;
        }


        $conditions = ['id' => 1];
        $values = [
            'heading1' => $request->heading,
            'subheading1' => $request->subheading,
            'image1' => $bannerpath1,
            'image1_alt' => $request->image1_alt,
            'btn_name' => $request->btn_name,
            'btn_link' => $request->btn_link,
            'description1' => $request->description,
            'heading2' => $request->heading2,
            'subheading2' => $request->subheading2,
            'image2' => $bannerpath2,
            'image2_alt' => $request->image2_alt,
            'btn_name2' => $request->btn_name2,
            'btn_link2' => $request->btn_link2,
            'description2' => $request->description2,
        ];
        HomeSection6::updateOrInsert($conditions, $values);
        return redirect()->back()->with('success', 'Data added successfully');
    }

    ## home page shop by category section
    public function shopByCateList()
    {
        $data = [
            "title" => 'Shop by category list',
            "viewurl" => 'admin.shopbycat.view',
            "editurl" => 'admin.shopbycat.edit',
            'list' => ShopByCategoryHomePage::orderBy('id', 'desc')->get(),
        ];
        return view('admin.shop_by_cat_homeList', $data);
    }

    public function shopBycatView()
    {
        $data = [
            'url_action' => route('admin.shopbycat.add'),
            'backtrack' => 'admin.shopbycat.list',
            'title' => 'Add Shopy by category',
            "obj" => '',
        ];
        return view('admin.shopbycat', $data);
    }

    public function addShopByCat(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5048', // Adjust mime types and max size as needed
        ], [
            'title.required' => 'The title field is required.',
            // 'image.required' => 'An image is required.',
            // 'image.image' => 'The uploaded file must be an image.',
            // 'image.mimes' => 'The image must be a JPEG, PNG, JPG, WEBP or GIF file.',
            // 'image.max' => 'The image size must not exceed 5 MB.',
        ]);

        // if ($request->file('image') != NULL) {
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     $fileName = "ring_style_" . time() . '.' . $extension;
        //     $path = $request->file('image')->storeAs('public/images/home', $fileName, 's3');
        //     Storage::disk('s3')->setVisibility($path, 'public');
        //     $imagepath = 'images/home/' . $fileName;
        // }
        $shopbyshape = new ShopByCategoryHomePage;
        $shopbyshape->title = $request->title;
        $shopbyshape->link = $request->link;
        $shopbyshape->order_number = $request->order_number ?? 0;
        // $shopbyshape->image = $imagepath;
        $shopbyshape->image_alt = $request->image_alt;;
        $shopbyshape->status = $request->status ?? 'false';
        $shopbyshape->save();
        return redirect()->back()->with('success', 'Data added successfully');
    }

    public function editShopByCat($id)
    {

        $editdata = ShopByCategoryHomePage::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('admin.shopbycat.update', ['id' => $editdata['id']]),
            'backtrack' => 'admin.shopbycat.list',
            'title' => 'Edit Shop by category',
            'obj' => $editdata,
        ];
        return view('admin.shopbycat', $data);
    }

    public function updateShopByCat(Request $request, $id)
    {

        $obj = ShopByCategoryHomePage::find($id);
        $this->validate($request, [
            'title' => 'required',
        ], [
            'title.required' => 'The title field is required.',
        ]);

        // if ($request->file('image') != NULL) {
        //     $oldImagePath = 'public/' . $obj->icon; // Replace with the actual path
        //     if (Storage::exists($oldImagePath)) {
        //         Storage::delete($oldImagePath);
        //     }
        //     $extension = $request->file('image')->getClientOriginalExtension();
        //     $fileName = "ring_style_" . time() . '.' . $extension;
        //     $path = $request->file('image')->storeAs('public/images/ringMetal', $fileName);
        //     $imagepath = 'images/ringMetal/' . $fileName;
        // } else {
        //     $imagepath = $obj->icon;
        // }
        $obj->title = $request->title;
        $obj->link = $request->link;
        $obj->order_number = $request->order_number;
        // $obj->image = $imagepath;
        $obj->image_alt = $request->image_alt;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'Data updated successfully');
    }

    public function sendTestEmail()
    {
        $details = [
            'subject' => 'Test Email',
            'body' => 'This is a test email using AWS SES without hardcoded credentials.',
        ];

        Mail::to('shubham.swapit@gmail.com')->send(new \App\Mail\TestEmail($details));

        return 'Email Sent';
    }
}
