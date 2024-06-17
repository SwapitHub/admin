<?php

	namespace App\Http\Controllers;

	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\File;
	use Illuminate\Support\Facades\Storage;
	use Illuminate\Support\Facades\Validator;
	use App\Models\DiamondShape;
	use App\Models\DiamondModel;

	class DiamondController extends Controller
	{
		public function index()
		{
		    $data['shapeList'] = DiamondShape::orderBy('id','desc')->paginate(10);
			return view('admin.diamondshape',$data);
		}

		public function shapeAddView()
		{
			return view('admin/add_diamondshape');
		}

		public function shapeAdd(Request $request)
		{
			$this->validate($request, [
            'shape' => 'required',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5048', // Adjust mime types and max size as needed
			], [
            'shape.required' => 'The shape field is required.',
            'name.required' => 'The shape field is required.',
            'image.required' => 'An image is required.',
            'image.image' => 'The uploaded file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, JPG, WEBP or GIF file.',
            'image.max' => 'The image size must not exceed 5 MB.',
			]);

			if ($request->file('image') != NULL) {
				$extension = $request->file('image')->getClientOriginalExtension();
				$fileName = "diamond_shape_" . time() . '.' . $extension;
                $path = $request->file('image')->storeAs('public/images/diamondShape', $fileName, 's3');
                $imagepath = 'images/diamondShape/' . $fileName;
                Storage::disk('s3')->setVisibility($path, 'public');
			}
			$shape = new DiamondShape;
			$shape->shape = $request->shape;
			$shape->name = $request->name;
			$shape->redirect_to = $request->redirect_to;
			$uniqueslug = $request->slug ?? $request->shape;
			$slug = $shape->generateUniqueSlug($uniqueslug);
			$shape->slug = $slug;
			$shape->order_number = $request->order_number;
			$shape->icon = $imagepath;
			$shape->status = $request->status ?? 'false';
			$shape->save();
			return redirect()->back()->with('success', 'Diamond shape added successfully');
		}

		public function editShapeView($id)
		{
			$data['shapedata'] = DiamondShape::find($id);
			return view('admin/edit_diamondshape',$data);
		}

		public function editShape(Request $request)
		{
			$this->validate($request, [
            'id' => 'required',
            'shape' => 'required',
            'name' => 'required',
			], [
            'id.required' => 'The shape id is required.',
            'shape.required' => 'The shape field is required.',
            'name.required' => 'The Name field is required.',
			]);

			$shape = DiamondShape::find($request->id);
			if ($request->file('image') != NULL) {
                $oldImagePath = $shape->icon;
                if ($oldImagePath) {
                    $oldImagePath = 'public/storage/' . $oldImagePath;
                    Storage::disk('s3')->delete($oldImagePath);
                }
				$extension = $request->file('image')->getClientOriginalExtension();
				$fileName = "diamond_shape_" . time() . '.' . $extension;
                $path = $request->file('image')->storeAs('public/images/diamondShape', $fileName, 's3');
                $imagepath = 'images/diamondShape/' . $fileName;
                Storage::disk('s3')->setVisibility($path, 'public');
			}
			else
			{
				$imagepath = $shape->icon;
			}

			$shape->shape = $request->shape;
            $shape->name = $request->name;
			$shape->redirect_to = $request->redirect_to;
			if($request->slug){ $slug = $request->slug; }else{ $slug= $shape->slug;}
			$shape->slug = $slug;
			$shape->order_number = $request->order_number;
			$shape->icon = $imagepath;
			$shape->status = $request->status ?? 'false';
			$shape->save();
			return redirect()->back()->with('success', 'Diamond shape updated successfully');
		}

		public function deleteShape($id)
		{
			if ($id) {
				$shape = DiamondShape::find($id);
				$oldImagePath = 'public/' . $shape->icon; // Replace with the actual path
				if (Storage::exists($oldImagePath)) {
					Storage::delete($oldImagePath);
				}
				$shape->delete();
				$output['res'] = 'success';
				$output['msg'] = 'Data Deleted';
				} else {
				$output['res'] = 'error';
				$output['msg'] = 'Diamond shape Id Required';
			}
			echo json_encode($output);
		}


		public function diamondList()
		{
			$diamond = new DiamondModel;
			$data = [
			"title"=>'Diamond list',
			"viewurl" => '',
			"editurl" =>'',
			'list'=> DiamondModel::orderBy('id','desc')->get(),
			];
			return view('admin.diamondList',$data);
		}
	}
