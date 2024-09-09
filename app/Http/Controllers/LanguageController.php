<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Language;
use App\Mail\UserRegistered;
use App\Mail\OrdersEmail;
use App\Mail\AbandoneCart;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Cart;
use App\Models\OrderModel;
use App\Models\SiteInfo;
use App\Models\OrderItem;
use App\Models\AddresModel;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApiDataExport;

class LanguageController extends Controller
{

    public function export()
    {
        //   // Fetch data from the API
        //   $response = Http::get('https://api.example.com/data');
        //   $data = $response->json();


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://apiservices.vdbapp.com/v2/gemstones?markup_mode=true',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Token token=CX7r3wiul169qAGnMjzlZm8iEpJIMAgks_IgGD0hywg, api_key=_amT48wMLQ3rh4SP1inCzRQ'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $data = json_decode($response);
        $values = $data->response->body->gemstones;
        // Store the data in a file and return it
        return Excel::download(new ApiDataExport($values), 'api_data.xlsx');
    }

    public function sendMail()
    {
        $siteinfo = SiteInfo::first();
        $siteinfo['logo'] = env('AWS_URL').'public/storage/'.$siteinfo['logo'];
        $emailContent = EmailTemplate::where('group', 'send_abandoned_cart_email_to_customer')->first();
        $user = User::find(57);
        $cartdata = Cart::where('user_id',$user->id)->get();

        // Mail::to($user->email)->send(new AbandoneCart($orderdata));
        return view('emails.abandoned_cart')
            ->with([
                'username' => $user->first_name . ' ' . $user->last_name,
                'siteinfo' => $siteinfo,
                'emailContent' => $emailContent,
                'cartdata' => $cartdata,
            ]);
    }
    public function index()
    {
        $data['langlist'] = Language::orderBy('id', 'desc')->get();
        return view('admin.languages', $data);
    }

    public function addLangView()
    {
        return view('admin.addLanguage');
    }
    public function addLanguage(REQUEST $request)
    {
        $this->validate($request, [
            'lang_name' => 'required',
            'code' => 'required',
            'order_number' => 'required',
            'icon' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5048', // Adjust mime types and max size as needed
        ], [
            'lang_name.required' => 'The language name field is required.',
            'icon.required' => 'An icon is required.',
            'icon.image' => 'The uploaded file must be an image.',
            'icon.mimes' => 'The image must be a JPEG, PNG, JPG, WEBP or GIF file.',
            'icon.max' => 'The image size must not exceed 5 MB.',
        ]);

        if ($request->file('icon') != NULL) {
            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileName = "langicon_" . time() . '.' . $extension;
            $path = $request->file('icon')->storeAs('public/images/lang', $fileName);
            $icon_path = 'images/lang/' . $fileName;
        }
        $lang = new language;
        $lang->lang_name = $request->lang_name;
        $lang->code = $request->code;
        $lang->icon = $icon_path;
        $lang->order_number = $request->order_number;
        $lang->status = $request->status ?? 'false';
        $lang->save();
        return redirect()->back()->with('success', 'Language added successfully');
    }

    public function editLang($id)
    {
        $data['langdata'] = Language::find($id);
        return view('admin.editlang', $data);
    }

    public function postEditLang(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'lang_name' => 'required',
            'code' => 'required',
            'order_number' => 'required',
        ], [
            'id.required' => 'The language id field is required.',
            'lang_name.required' => 'The language name field is required.',
        ]);
        $lang = Language::find($request->id);
        if ($request->file('icon') != NULL) {

            $oldImagePath = 'public/' . $lang->icon; // Replace with the actual path
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }

            $extension = $request->file('icon')->getClientOriginalExtension();
            $fileName = "langicon_" . time() . '.' . $extension;
            $path = $request->file('icon')->storeAs('public/images/lang', $fileName);
            $icon_path = 'images/lang/' . $fileName;
        } else {
            $icon_path = $lang->icon;
        }
        $lang->lang_name = $request->lang_name;
        $lang->code = $request->code;
        $lang->icon = $icon_path;
        $lang->order_number = $request->order_number;
        $lang->status = $request->status ?? 'false';
        $lang->save();
        return redirect()->back()->with('success', 'Language updated successfully');
    }

    public function deleteLang($id)
    {
        if ($id) {
            $langdata = Language::find($id);
            $oldImagePath = 'public/' . $langdata->icon; // Replace with the actual path
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }
            $langdata->delete();
            $output['res'] = 'success';
            $output['msg'] = 'Data Deleted';
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Language Id Required';
        }
        echo json_encode($output);
    }
}
