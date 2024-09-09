<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Admin;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\TransactionModel;
use App\Models\Widget;
use App\Models\Cmscontent;
use App\Models\ContactUs;
use App\Models\User;
use App\Library\UpsShipping;
use App\Library\Clover;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->productCount = ProductModel::count();
        $this->widget = Widget::count();
        $this->contactMsg = ContactUs::where('type', 'general')->count();
        $this->users = User::count();
        $this->orders = OrderModel::count();
        $this->trnsactions = TransactionModel::count();
        $this->transactions = TransactionModel::latest()->take(5)->get();
        $this->latest_order = OrderModel::latest()->take(5)->get();
        $this->dailysales = $this->getTodayTransactions();
        $this->monthlysales = $this->getMonthlyTransactions();
        $this->averageBasket = $this->averageBasketYTD();
    }

    ## average Basket YTD
    public function averageBasketYTD()
    {
        // Define the start of the year
        $startOfYear = Carbon::now()->startOfYear();

        // Calculate the total value and count of orders since the start of the year
        $totalValue = OrderModel::where('created_at', '>=', $startOfYear)->sum('amount'); // Adjust the 'total' field as per your application
        $orderCount = OrderModel::where('created_at', '>=', $startOfYear)->count();

        // Calculate the average basket value
        return $averageBasket = $orderCount > 0 ? $totalValue / $orderCount : 0;

    }
    ## get daily sales
    public function getTodayTransactions()
    {
        $today = Carbon::today();
        $totalAmount = TransactionModel::whereDate('created_at', $today)
            ->sum('amount');
        return $formattedAmount = number_format($totalAmount, 2);
    }

    ## get monthly sales
    public function getMonthlyTransactions()
    {
        // Get the start and end dates of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Query to get all transactions of the current month and aggregate the amount
        $totalAmount = TransactionModel::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');
        return $formattedAmount = number_format($totalAmount, 2);
    }

    public function dashboard()
    {
        $data = [
            'productCount' => $this->productCount,
            'widget' => $this->widget,
            'contactMsg' =>  $this->contactMsg,
            'users' =>  $this->users,
            'orders' => $this->orders,
            'trnsactions' => $this->trnsactions,
            'latest_orders' => $this->latest_order,
            'dalysales' => $this->dailysales,
            'monthlysales' =>  $this->monthlysales,
            'averageBasket' =>  $this->averageBasket,
            'transactions' =>   $this->transactions,
            'ring_sizer'=> Cmscontent::where('id',7)->first()
        ];
        return view('admin.dashboard', $data);
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'adminname' => 'required',
            'email' => 'required|email',
            'designation' => 'required',
        ], [
            'id.required' => 'The admin id field is required.',
            'adminname.required' => 'The admin name field is required.',
            'designation.required' => 'The admin designation field is required.',
            'email.required' => 'The email field is required.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $firstError = $errors->first();
            $output['res'] = 'error';
            $output['msg'] = $firstError;
        } else {
            $admin = new Admin;
            $admin = Admin::find($request->id);
            $admin->name = $request['adminname'];
            $admin->email = $request['email'];
            $admin->designation = $request['designation'];
            $admin->save();
            $output['res'] = 'success';
            $output['msg'] = 'Profile updated successfully';
        }
        echo json_encode($output);
    }

    public function changePassword(Request $request)
    {
        $admin = new Admin;
        $admin = Admin::find($request->id);
        if (Hash::check($request->old_password, $admin->password)) {
            $validator = Validator::make($request->all(), [
                'password' => 'required|min:5|confirmed', // Adding password and confirmation validation
            ], [
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 5 characters long.',
                'password.confirmed' => 'The password confirmation does not match.',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $firstError = $errors->first();
                $output['res'] = 'error';
                $output['msg'] = $firstError;
            } else {
                // update password;
                $admin->password =  Hash::make($request->password);
                $admin->save();
                $output['res'] = 'success';
                $output['msg'] = 'password updated';
            }
        } else {
            // old password not matched;
            $output['res'] = 'error';
            $output['msg'] = 'old password not matched';
        }
        echo json_encode($output);
    }

    public function updateIcon(Request $request)
    {
        $admin = Admin::find($request->id);

        if ($request->hasFile('icon')) {
            $oldImagePath = $admin->designation_icon;
            $fileName = time() . '_' . $request->file('icon')->getClientOriginalName();
            // $filePath = Storage::disk('s3')->url('public/storage/images/dashboard/' . $fileName);
            $path = $request->file('icon')->storeAs('public/storage/images/dashboard', $fileName, 's3');
            $filepath = 'images/dashboard/' . $fileName;
            Storage::disk('s3')->setVisibility($path, 'public');
            // // Delete old image from S3
            if ($oldImagePath) {
                $oldImagePath = 'public/storage/' . $oldImagePath;
                Storage::disk('s3')->delete($oldImagePath);
            }
            $output['msg'] = 'Icon updated';
        } else {
            $filepath = $admin->designation_icon;
            $output['msg'] = 'No changes';
        }
        $admin->designation_icon = $filepath;
        $admin->save();
        $output['res'] = 'success';
        echo json_encode($output);
    }

    public function createCharge()
    {
        $chargeData = [
            'amount' => 100,
            'card_token' => 'clv_1TSTSV9UGpCo5MP2rntwX1CW',
            'email' => 'test@gmail.com',
        ];
        $clover = new Clover();
        $result = $clover->createCharge($chargeData);
        echo "<pre>";
        var_dump($result);
    }
}
