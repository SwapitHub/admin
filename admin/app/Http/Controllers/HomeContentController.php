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
        ];
        $banner = Banner::orderBy('id', 'desc')->where('type', 'Home')->get();
        $data = [
            "title" => 'Home Content',
            "url_action" => route('admin.homecontent.update'),
            'bannerlist' => $banner,
            'sectionList' => $sectionData,
            "obj" => HomeContent::find(1)
        ];
        return view('admin.homecontant', $data);
    }

    // public function update(Request $request)
    // {
    // 	// get all the images
    // 	$homecontant = HomeContent::find(1);
    // 	// main_banner
    // 	if ($request->file('main_banner') != NULL) {
    // 		$oldImagePath = $homecontant->main_banner; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('main_banner')->getClientOriginalExtension();
    // 		$fileName = "main_banner_" . time() . '.' . $extension;
    // 		$path = $request->file('main_banner')->storeAs('public/storage/images/homeContent', $fileName,'s3');

    // 		$main_banner = 'images/homeContent/' . $fileName;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $main_banner = $homecontant->main_banner;
    // 	}



    // 	// sale banner
    // 	if ($request->file('sale_banner') != NULL) {
    // 		$oldImagePath = $homecontant->sale_banner; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('sale_banner')->getClientOriginalExtension();
    // 		$fileName_sale = "sale_banner_" . time() . '.' . $extension;
    // 		$path = $request->file('sale_banner')->storeAs('public/storage/images/homeContent', $fileName_sale,'s3');
    // 		$sale_banner = 'images/homeContent/' . $fileName_sale;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $sale_banner = $homecontant->sale_banner;
    // 	}

    // 	// ring_promotion_banner_desktop_1
    // 	if ($request->file('ring_promotion_banner_desktop_1') != NULL) {
    // 		$oldImagePath = $homecontant->ring_promotion_banner_desktop_1; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_desktop_1')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_desktop_1 = "ring_promotion_banner_desktop_1_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_desktop_1')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_desktop_1, 's3');
    // 		$ring_promotion_banner_desktop_1 = 'images/homeContent/' . $fileName_ring_promotion_banner_desktop_1;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_desktop_1 = $homecontant->ring_promotion_banner_desktop_1;
    // 	}


    // 	// ring_promotion_banner_mobile_1
    // 	if ($request->file('ring_promotion_banner_mobile_1') != NULL) {
    // 		$oldImagePath = $homecontant->ring_promotion_banner_mobile_1; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_mobile_1')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_mobile_1 = "ring_promotion_banner_mobile_1_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_mobile_1')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_mobile_1);
    // 		$ring_promotion_banner_mobile_1 = 'images/homeContent/' . $fileName_ring_promotion_banner_mobile_1;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_mobile_1 = $homecontant->ring_promotion_banner_mobile_1;
    // 	}

    // 	//ring_promotion_banner_desktop_2
    // 	if ($request->file('ring_promotion_banner_desktop_2') != NULL) {
    // 		$oldImagePath = $homecontant->ring_promotion_banner_desktop_2; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_desktop_2')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_desktop_2 = "ring_promotion_banner_desktop_2_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_desktop_2')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_desktop_2, 's3');
    // 		$ring_promotion_banner_desktop_2 = 'images/homeContent/' . $fileName_ring_promotion_banner_desktop_2;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_desktop_2 = $homecontant->ring_promotion_banner_desktop_2;
    // 	}

    // 	//ring_promotion_banner_mobile_2
    // 	if ($request->file('ring_promotion_banner_mobile_2') != NULL) {
    // 		$oldImagePath = $homecontant->ring_promotion_banner_mobile_2; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_mobile_2')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_mobile_2 = "ring_promotion_banner_mobile_2_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_mobile_2')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_mobile_2,'s3');
    // 		$ring_promotion_banner_mobile_2 = 'images/homeContent/' . $fileName_ring_promotion_banner_mobile_2;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_mobile_2 = $homecontant->ring_promotion_banner_mobile_2;
    // 	}

    // 	// ring_promotion_banner_desktop_3
    // 	if ($request->file('ring_promotion_banner_desktop_3') != NULL) {
    // 		$oldImagePath = $homecontant->ring_promotion_banner_desktop_3; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_desktop_3')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_desktop_3 = "ring_promotion_banner_desktop_3_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_desktop_3')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_desktop_3,'s3');
    // 		$ring_promotion_banner_desktop_3 = 'images/homeContent/' . $fileName_ring_promotion_banner_desktop_3;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_desktop_3 = $homecontant->ring_promotion_banner_desktop_3;
    // 	}

    // 	//ring_promotion_banner_mobile_3
    // 	if ($request->file('ring_promotion_banner_mobile_3') != NULL) {
    // 		$oldImagePath =  $homecontant->ring_promotion_banner_mobile_3; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_mobile_3')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_mobile_3 = "ring_promotion_banner_mobile_3_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_mobile_3')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_mobile_3,'s3');
    // 		$ring_promotion_banner_mobile_3 = 'images/homeContent/' . $fileName_ring_promotion_banner_mobile_3;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_mobile_3 = $homecontant->ring_promotion_banner_mobile_3;
    // 	}

    // 	// ring_promotion_banner_last
    // 	if ($request->file('ring_promotion_banner_last') != NULL) {
    // 		$oldImagePath =  $homecontant->ring_promotion_banner_last; // Replace with the actual path
    // 		if ($oldImagePath) {
    //             $oldImagePath = 'public/storage/'.$oldImagePath;
    //             Storage::disk('s3')->delete($oldImagePath);
    // 		}
    // 		$extension = $request->file('ring_promotion_banner_last')->getClientOriginalExtension();
    // 		$fileName_ring_promotion_banner_last = "ring_promotion_banner_last_" . time() . '.' . $extension;
    // 		$path = $request->file('ring_promotion_banner_last')->storeAs('public/storage/images/homeContent', $fileName_ring_promotion_banner_last, 's3');
    // 		$ring_promotion_banner_last = 'images/homeContent/' . $fileName_ring_promotion_banner_last;
    //         Storage::disk('s3')->setVisibility($path, 'public');
    // 	}else{
    // 	    $ring_promotion_banner_last = $homecontant->ring_promotion_banner_mobile_3;
    // 	}

    // 	$homecontant->main_banner = $main_banner;
    // 	$homecontant->main_banner_title = $request->main_banner_title;
    // 	$homecontant->main_banner_subtitle = $request->main_banner_subtitle;
    // 	$homecontant->main_banner_links = $request->main_banner_links;
    // 	$homecontant->sale_banner = $sale_banner;
    // 	$homecontant->sale_banner_heading = $request->sale_banner_heading;
    // 	$homecontant->sale_banner_link = $request->sale_banner_link;
    // 	$homecontant->sale_banner_desc = $request->sale_banner_desc;
    // 	$homecontant->sale_banner_alt = $request->sale_banner_alt;
    // 	$homecontant->ring_promotion_banner_desktop_1 = $ring_promotion_banner_desktop_1;
    // 	$homecontant->ring_promotion_banner_mobile_1 = $ring_promotion_banner_mobile_1;
    // 	$homecontant->ring_promotion_banner_alt_1 = $request->ring_promotion_banner_alt_1;
    // 	$homecontant->ring_promotion_banner_title_1 = $request->ring_promotion_banner_title_1;
    // 	$homecontant->ring_promotion_banner_desc_1 = $request->ring_promotion_banner_desc_1;
    // 	$homecontant->ring_promotion_banner_link_1 = $request->ring_promotion_banner_link_1;
    // 	$homecontant->ring_promotion_banner_desktop_2 = $ring_promotion_banner_desktop_2;
    // 	$homecontant->ring_promotion_banner_mobile_2 = $ring_promotion_banner_mobile_2;
    // 	$homecontant->ring_promotion_banner_alt_2 = $request->ring_promotion_banner_alt_2;
    // 	$homecontant->ring_promotion_banner_title_2 = $request->ring_promotion_banner_title_2;
    // 	$homecontant->ring_promotion_banner_desc_2 = $request->ring_promotion_banner_desc_2;
    // 	$homecontant->ring_promotion_banner_link_2 = $request->ring_promotion_banner_link_2;
    // 	$homecontant->ring_promotion_banner_desktop_3 = $ring_promotion_banner_desktop_3;
    // 	$homecontant->ring_promotion_banner_mobile_3 = $ring_promotion_banner_mobile_3;
    // 	$homecontant->ring_promotion_banner_alt_3 = $request->ring_promotion_banner_alt_3;
    // 	$homecontant->ring_promotion_banner_title_3 = $request->ring_promotion_banner_title_3;
    // 	$homecontant->ring_promotion_banner_desc_3 = $request->ring_promotion_banner_desc_3;
    // 	$homecontant->ring_promotion_banner_link_3 = $request->ring_promotion_banner_link_3;
    // 	$homecontant->ring_promotion_banner_last = $ring_promotion_banner_last;
    // 	$homecontant->ring_promotion_banner_alt = $request->ring_promotion_banner_alt;
    // 	$homecontant->ring_promotion_banner_desc = $request->ring_promotion_banner_desc;
    // 	$homecontant->save();
    // 	return redirect()->back()->with('success', 'Data updated successfully');
    // }

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
        }else
        {
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
        }
        else
        {
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
        }
        else
        {
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
        }
        else
        {
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
        }
        else
        {
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
        }
        else
        {
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
        }
        else
        {
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
}
