<?php

	namespace App\Http\Controllers\API;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use App\Models\Cmscategory;
	use App\Models\Cmscontent;
	use App\Models\Faq;
	use App\Models\Widget;
	use Illuminate\Support\Facades\Session;

	class PageController extends Controller
	{
		public function index()
		{
			$output['res'] = 'success';
			$output['msg'] = 'data retrieved successfully';

            $headings = Cmscategory::orderBy('order_number','asc')->where('status','true')->where('category',6)->get();
            foreach($headings as $heading)
            {
                $heading['pages'] =  Cmscontent::orderBy('order_number','asc')->where('status','true')->where('cms_category',$heading->id)->get();
            }
			$output['data'] = $headings;
			return response()->json($output, 200);
		}

		public function contactFaq()
		{
			$output['res'] = 'success';
			$output['msg'] = 'data retrieved successfully';
            $faq = Faq::orderBy('order_number','asc')->where('faq_category',6)->get();
			$output['data'] = $faq;
			return response()->json($output, 200);
		}

        public function widgetCallByName($name)
        {
            if($name)
            {
                $output['res'] = 'success';
                $output['msg'] = 'data retrieved successfully';
                $widgetx =  Widget::where('name',$name);
                if($widgetx->exists())
                {
                    $output['data'] = $widgetx->first();
                }else
                {
                    $output['res'] = 'error';
                    $output['msg'] = 'data not available.';
                }

            }
            else{
                $output['res'] = 'error';
                $output['msg'] = 'Invalid name';
            }
            return response()->json($output, 200);
        }



	}
