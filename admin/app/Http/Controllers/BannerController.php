<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Validator;
	use App\Models\Banner;
    use App\Models\Cmscategory;

	class BannerController extends Controller
	{
		public function index()
		{
			$banner = Banner::orderBy('id', 'desc')->where('type','!=','Home')->get();
			$data = [
            'bannerlist' => $banner,
			];
			return view('admin.bannerList', $data);
		}
		public function addbanner()
		{
            $data['pages'] = Cmscategory::where('status','true')->get();
			return view('admin.addbanner',$data);
		}

		public function postAddBanner(Request $request)
		{
			$this->validate($request, [
            'title' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5048', // Adjust mime types and max size as needed
			'termcondition' => $request->type == 'promotional' ? 'required' : '', // Conditional validation
			], [
            'title.required' => 'The banner title field is required.',
            'image.required' => 'An image is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, WEBP or GIF file.',
            'image.max' => 'The image size must not exceed 5 MB.',
			'termcondition.required' => 'The terms and conditions field is required for promotional banners.',
			]);

			if ($request->file('image') != NULL) {
				$extension = $request->file('image')->getClientOriginalExtension();
				$fileName = "banner_" . time() . '.' . $extension;
                $path = $request->file('image')->storeAs('public/images/banner', $fileName,'s3');
                Storage::disk('s3')->setVisibility($path, 'public');
				$bannerpath = 'images/banner/' . $fileName;

			}
			$banner = new Banner;
			$banner->title = $request->title;
			$banner->type = $request->type;
			$banner->term_condition = $request->termcondition;
			$banner->subtitle = $request->subtitle;
			$banner->link = $request->link;
			$banner->btn_name = $request->btn_name;
			$banner->banner = $bannerpath;
			$banner->description = $request->description;
			$banner->page = $request->page;
			$banner->position = $request->position;
			$banner->status = $request->status ?? 'false';
			$banner->save();
			return redirect()->back()->with('success', 'Banner added successfully');
		}

		public function deleteBanner($id)
		{
			if ($id) {
				$bannerdata = Banner::find($id);
				$oldImagePath = 'public/' . $bannerdata->banner; // Replace with the actual path
				if (Storage::exists($oldImagePath)) {
					Storage::delete($oldImagePath);
				}
				$bannerdata->delete();
				$output['res'] = 'success';
				$output['msg'] = 'Data Deleted';
				} else {
				$output['res'] = 'error';
				$output['msg'] = 'Banner Id Required';
			}
			echo json_encode($output);
		}

		public function editBanner($id)
		{
			$bannerdata = Banner::find($id);
            $data['pages'] = Cmscategory::where('status','true')->get();
			$data['bannerdata'] = $bannerdata;
			return view('admin.editbanner', $data);
		}

		public function postEditBanner(Request $request)
		{
			$this->validate($request, [
            'id' => 'required',
            'title' => 'required',
			'termcondition' => $request->type == 'promotional' ? 'required' : '', // Conditional validation
			], [
            'id.required' => 'The banner id  is required.',
            'title.required' => 'The banner title field is required.',
			'termcondition.required' => 'The terms and conditions field is required for promotional banners.',
			]);
			$bannerdata = Banner::find($request->id);
			if ($request->file('image') != NULL) {
                $oldImagePath = $bannerdata->banner;
                if ($oldImagePath) {
                    $oldImagePath = 'public/' . $oldImagePath;
                    Storage::disk('s3')->delete($oldImagePath);
                }
				$extension = $request->file('image')->getClientOriginalExtension();
				$fileName = "banner_" . time() . '.' . $extension;
				$path = $request->file('image')->storeAs('public/images/banner', $fileName,'s3');
				$bannerpath = 'images/banner/' . $fileName;
                Storage::disk('s3')->setVisibility($path, 'public');
			}else{
			    $bannerpath = $bannerdata->banner;
			}

			$bannerdata->title = $request->title;
			$bannerdata->subtitle = $request->subtitle;
			$bannerdata->type = $request->type;
			$bannerdata->term_condition = ($request->type =='general')?'':$request->termcondition;
			$bannerdata->link = $request->link;
            $bannerdata->btn_name = $request->btn_name;
			$bannerdata->banner = $bannerpath;
			$bannerdata->description = $request->description;
			$bannerdata->page = $request->page;
			$bannerdata->position = $request->position;
			$bannerdata->status = $request->status ?? 'false';
			$bannerdata->save();
			return redirect()->back()->with('success', 'Banner updated successfully');
		}
	}
