<?php

	namespace App\Http\Controllers\API;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Models\Banner;
	use Validator;

	class BannerController extends Controller
	{
		public function index()
		{
			$output['res'] = 'success';
			$output['msg'] = 'data retrieved successfully';

			$data = Banner::orderBy('order_number','desc')->where('type','home')->where('status','true')->get();
			foreach($data as $banner){
			  $banner->banner =env('AWS_URL').'public/'.$banner->banner;
			}
			$output['data'] = $data;
			// return response()->json($output, 200);
            return response()->json($output)
            ->header('Cache-Control', 'max-age=86400, public');
		}
	}
