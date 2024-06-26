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

class ProductImport1 implements ToCollection, WithHeadingRow
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
                //    dd($input);
                $values = $input['subcategory'];
                $splitValues = explode('/', $values);
                $input['menu'] = $this->menu_id;
                $response =  $this->fetchCatSubcatValues($splitValues);
                $input['category'] = $response['cat'];
                $input['sub_category'] = $response['subcat'];
                $input['slug'] = $product->generateUniqueSlug(!empty($input['product_browse_pg_name'])?$input['product_browse_pg_name']:$input['name']);
                if($input['parent_sku'] == $input['sku'])
                {
                    $input['parent_sku'] = null;
                }
                if($input['parent_sku'] == null){
					$input['type'] = 'parent_product';
					}else{
					$input['type'] = 'child_product';
				}
                $input['internal_sku'] = $input['sku'];
                $input['videos'] = ($input['videos'] != null) ? $product->sortVideos($input['videos']) : null;
                $input['images'] = (!empty($input['images']))?json_encode(explode(',', $input['images'])):null;
                $input['metalType_id'] = $this->getMetalTypeIdByName('18 Kt');
                $input['metalColor_id'] = $this->getMetalColorIdByName($input['metalcolor']);
                $input['status'] = 'true';
                unset($input['subcategory']);

                $matchData = [
                    'sku' => $input['sku'],
                ];
                $saved = ProductModel::updateOrCreate($matchData, $input);
                if (!$saved) {
                    $stat = 'false';
                }
                var_dump($saved);


            //    dd($input);
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

    ## get menu cat and subcat
    public function fetchCatSubcatValues($values)
    {
        //    var_dump($values);
        $cat_id = $this->createOrFetchCategory($values[1]);
        if (isset($values[2]) && !empty($values[2])) {
           $subcat_id =  $this->createOrFetchSubCategory($cat_id,$values[2]);
        }
        return ['cat'=>$cat_id,'subcat'=>$subcat_id??null];
    }

    ## get product category and subcategories
    // public function fetchCategoryValue($categoryvalue)
    // {
    //     $catvalue = explode(',', $categoryvalue);
    //     $catval = $catvalue[0];
    //     $values = explode('/', $catval);
    //     $category = isset($values[0]) ? $values[0] : null;
    //     $subcategory = isset($values[1]) ? $values[1] : null;
    //     $response_cat = $this->createOrFetchCategory($category);
    //     if ($response_cat) {
    //         if ($subcategory != null) {
    //             $response_subcat =  $this->createOrFetchSubCategory($response_cat, $subcategory);
    //         } else {
    //             $response_subcat = null;
    //         }
    //     }
    //     return $retuen_arr = [
    //         'category' => $response_cat,
    //         'sub_category' => $response_subcat
    //     ];
    // }

    ## check if these category and subcategory are exist in db then get the ids otherwise added them in db and get id
    public function createOrFetchCategory($category)
    {
        $query = Category::where('menu', $this->menu_id)
            ->whereRaw('LOWER(name) = ?', [strtolower($category)]);

        if ($query->exists()) {
            $res = $query->first();
            return $res['id'];
        } else {
            $cat = new Category();
            $cat->menu = $this->menu_id;
            $cat->name = $category;
            $cat->slug = $cat->generateUniqueSlug($category);
            $cat->status = 'false';
            $cat->save();
            return  $cat->id;
        }
    }

    ## check if these category and subcategory are exist in db then get the ids otherwise added them in db and get id
    public function createOrFetchSubCategory($category, $subcategory)
    {
        $query = Subcategory::where('menu_id', $this->menu_id)
            ->where('category_id', $category)
            ->whereRaw('LOWER(name) = ?', [strtolower($subcategory)]);

        if ($query->exists()) {
            $res = $query->first();
            return $res['id'];
        } else {
            $subcat = new Subcategory();
            $subcat->menu_id = $this->menu_id;
            $subcat->category_id = $category;
            $subcat->name = $subcategory;
            $subcat->slug = $subcat->generateUniqueSlug($subcategory);
            $subcat->order_number = 0;
            $subcat->status = 'false';
            $subcat->meta_title = $subcategory;
            $subcat->meta_keyword = $subcategory;
            $subcat->meta_description = $subcategory;
            $subcat->save();
            return  $subcat->id;
        }
    }
}
