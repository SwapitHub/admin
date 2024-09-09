<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductImageModel;
use App\Models\ProductVideosModel;
use App\Models\MetalColor;
use App\Models\RingMetal;
use App\Models\ProductModel;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */

    protected $menu;
    protected $importedData;
    protected $importStatus;

    public function __construct($menu)
    {
        $this->menu = $menu;
        $this->menu_id = Menu::where('name', $menu)->first()['id'];
    }

    public function collection(Collection $collection)
    {
        $product = new ProductModel;
        $stat = 'true';
        foreach ($collection as $row) {
            if ($row->filter()->isNotEmpty()) {
                $input = $row->toArray();
                $input['sub_category'] = $this->getSubcategoryId($input['sub_category']);
                $input['menu'] =  $this->menu_id;
                $input['category'] = 7;
                // $input['videos'] = ($input['videos'] != null) ? $product->sortVideos($input['videos']) : null;
                $input['videos'] = ($input['videos'] != null) ? $product->sortVideos($input['videos']) : null;
                $input['slug'] = $product->generateUniqueSlug(!empty($input['product_browse_pg_name'])?$input['product_browse_pg_name']:$input['name']);

                if($input['parent_sku'] == NULL){
					$input['type'] = 'parent_product';
					}else{
					$input['type'] = 'child_product';
				}
                $images_arr = [];
                $images = explode(',',$input['images']);
                foreach($images as $img)
                {
                    $images_arr[] = $img;
                }
                $input['images'] = json_encode($images)??null;

                $matchData = ['sku'=>$input['sku']];
                $saved = ProductModel::updateOrCreate($matchData,$input);
                var_dump($saved);


            }
        }
    }




    ## get metal color id
    public function getMetalColorIdByName($name)
    {
        $query = MetalColor::where('name', $name);
        if ($query->exists()) {
            $metaldata  = $query->first();
            return $metaldata['id'];
        } else {
            $metalColor = new MetalColor();
            $metalColor->name = $name;
            $metalColor->status = 'false';
            $metalColor->order_number = 0;
            $metalColor->save();
            return $metalColor->id;
        }
    }

    ## get metal type id
    public function getMetalTypeIdByName($name)
    {
        $query = RingMetal::where('metal', $name);
        if ($query->exists()) {
            $metaltype  = $query->first();
            return $metaltype['id'];
        } else {
            $metaltype = new RingMetal();
            $metaltype->metal = $name;
            $metaltype->status = 'false';
            $metaltype->save();
            return $metaltype->id;
        }
    }

    ## get cubcategory by name and categoryif
    public function getSubcategoryId($subcatname)
    {
        $query =  Subcategory::where('menu_id', $this->menu_id)
            ->where('category_id', 7)
            ->where('name', $subcatname);
        if ($query->exists()) {
            $subcatdata = $query->first();
            return $subcat_id = $subcatdata['id'];
        }else
        {
            ## insert into category table where menu = $menu
    		$insertData = new Subcategory;
    		$insertData->menu_id = $this->menu_id;
    		$insertData->category_id = 7;
    		$insertData->name = $subcatname;
    		$slug = $insertData->generateUniqueSlug($subcatname);
    		$insertData->slug = $slug;
    		$insertData->alias = $slug;
    		$insertData->order_number = 0;
    		$insertData->status = 'true';
    		$insertData->save();
    		return $subcat_id = $insertData->id;
        }
    }

    // public function findSubcategory($categoryvalue)
    // {
    // 	$keyword_arr = ['3 Stone', 'Two-Stone Rings', 'Halo', 'Solitaires', 'Hidden Halo'];
    // 	$categoryval = explode('/', $categoryvalue);

    // 	foreach ($keyword_arr as $keyword) {
    // 		if (in_array($keyword, $categoryval)) {
    // 			$keyword =  ($keyword == '3 Stone') ? 'Three stone' : $keyword;
    // 			$query =  Subcategory::where('menu_id', $this->menu_id)
    // 				->where('category_id', 7)
    // 				->where('name', $keyword);
    // 			if ($query->exists()) {
    // 				$subcatdata = $query->first();
    // 				return $subcat_id = $subcatdata['id'];
    // 			}
    // 		}
    // 	}
    // }







    // public function getSubCategoryId($subcatname)
    // {
    // 	$query =  Subcategory::where('menu_id', $this->menu_id)
    // 		->where('category_id', 7)
    // 		->where('name', str_replace('-', ' ', $subcatname));
    // 	if ($query->exists()) {
    // 		$catdata = $query->first();
    // 		$cat_id = $catdata['id'];
    // 	} else {

    // 		// insert into category table where menu = $menu
    // 		$insertData = new Subcategory;
    // 		$insertData->menu_id = $this->menu_id;
    // 		$insertData->category_id = 7;
    // 		$insertData->name = $subcatname;
    // 		$slug = $insertData->generateUniqueSlug($subcatname);
    // 		$insertData->slug = $slug;
    // 		$insertData->alias = $slug;
    // 		$insertData->order_number = 0;
    // 		$insertData->status = 'false';
    // 		$insertData->save();
    // 		$cat_id = $insertData->id;
    // 	}
    // 	return $cat_id;
    // }


    // public function saveImages($images, $skufolder, $product_id)
    // {
    // 	$imageUrlArray = json_decode($images, true);
    // 	foreach ($imageUrlArray as $url) {
    // 		$filename = basename($url);
    // 		$matchData = [
    // 			'product_id' => $product_id,
    // 			'product_sku' => $skufolder,
    // 			'image_path' => $filename,
    // 		];
    // 		$data = [
    // 			'product_id' => $product_id,
    // 			'product_sku' => $skufolder,
    // 			'image_path' => $filename,
    // 			'status' => 'true',
    // 		];
    // 		if (ProductImageModel::updateOrCreate($matchData, $data)) {
    // 			return true;
    // 		}
    // 	}
    // }

    // public function saveVideos($videos, $skufolder, $product_id)
    // {
    // 	$videos = json_decode($videos, true);
    // 	foreach ($videos as $index => $url) {
    // 		$filename = basename($url);
    // 		$mathData = [
    // 			'product_id' => $product_id,
    // 			'product_sku' => $skufolder,
    // 			'color' => $index,
    // 			'video_path' => $filename,
    // 		];
    // 		$data = [
    // 			'product_id' => $product_id,
    // 			'product_sku' => $skufolder,
    // 			'color' => $index,
    // 			'video_pathx' => $filename,
    // 			'status' => 'true',
    // 		];

    // 		if (ProductVideosModel::updateOrCreate($mathData, $data)) {
    // 			return true;
    // 		}
    // 	}
    // }

    // public function getMetalType($metalType)
    // {
    //     $metal =  RingMetal::where('metal', trim($metalType));
    //     if ($metal->exists()) {
    //         // get the id
    //         $metaldata =  $metal->first();
    //         $metal_id = $metaldata['id'];
    //     } else {
    //         // insert into metal
    //         $metal = new RingMetal;
    //         $metal->metal = trim($metalType);
    //         $metal->status = 'false';
    //         $metal->order_number = 0;
    //         $metal->save();
    //         $metal_id = $metal->id;
    //     }

    //     return $metal_id;
    // }

    // public function getMetalColor($color)
    // {
    //     $metalcolor =  MetalColor::where('name', trim($color));
    //     if ($metalcolor->exists()) {
    //         // get the id
    //         $metalcolordata =  $metalcolor->first();
    //         $metalcolor_id = $metalcolordata['id'];
    //     } else {
    //         // insert into metal
    //         $metalcolor = new MetalColor;
    //         $metalcolor->name = trim($color);
    //         $metalcolor->status = 'false';
    //         $metalcolor->order_number = 0;
    //         $metalcolor->save();
    //         $metalcolor_id = $metalcolor->id;
    //     }

    //     return $metalcolor_id;
    // }
}
