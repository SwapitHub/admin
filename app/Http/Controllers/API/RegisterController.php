<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\HasApiTokens;
use App\Mail\UserRegistered;
use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;
use Validator;

class RegisterController extends BaseController
{

    public function ping()
    {
        return response()->json(['status' => 'ok'], 200);
    }
    // public function __destruct()
    // {
    //     $this->emailGroup = [
    //         'send_registratino_email',
    //         'send_account_verification_email',
    //         'send_registratino_email_to_admin'
    //     ];
    // }

    // public function registerUser(Request $request)
    // {
    // 	$validator = Validator::make($request->all(), [
    // 	'first_name' => 'required',
    // 	'last_name' => 'required',
    //     'email' => 'required|email|unique:users',
    //     'password' => 'required',
    //     'c_password' => 'required|same:password',
    // 	]);
    // 	if($validator->fails())
    // 	{
    // 		$output['res'] = 'error';
    // 		// $output['msg'] = $validator->errors();
    // 		$output['msg'] = $validator->errors()->all();
    // 		$output['data'] =[];
    // 		return response()->json($output, 400);
    // 	}
    // 	$input = $request->all();
    // 	$input['password'] = bcrypt($input['password']);
    // 	$user = User::create($input);
    // 	$output['res'] = 'success';
    // 	$output['msg'] = 'User register successfully.';
    // 	$output['data'] = ['user_id'=>$user->id,'user_name'=>$user->first_name,'user_email'=>$user->email];
    // 	return response()->json($output, 200);
    // }

    // public function login(Request $request)
    // {
    // 	if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    // 		$user = Auth::user();
    // 		if($user->status !='false')
    // 		{
    // 	        //  fetch cart data and send with output data
    // 			$output['res'] = 'success';
    // 			$output['msg'] = 'User login successfully.';
    // 			$output['data'] = ['user_id'=>$user->id,'user_name'=>$user->first_name,'user_email'=>$user->email,'cart_item'=>Cart::where('user_id',$user->id)->where('status','true')->count()];
    // 			return response()->json($output, 200);
    // 		}
    // 		else
    // 		{
    // 			$output['res'] = 'error';
    // 			$output['msg'] = 'Unauthorised User';
    // 			$output['data'] = [];
    // 			return response()->json($output,401);
    // 		}
    // 	}
    // 	else
    // 	{
    // 		$output['res'] = 'error';
    // 		$output['msg'] = 'Unauthorised';
    // 		$output['data'] = [];
    // 		return response()->json($output,401);
    // 	}
    // }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            $output['res'] = 'error';
            $output['msg'] = $validator->errors()->all();
            $output['data'] = [];
            return response()->json($output, 400);
        }

        $input = $request->all();
        $input['status'] = 'false';
        $input['email'] = strtolower($input['email']);
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        // Mail::to($user->email)->send(new UserRegistered($user));

        // Create token
        $token = $user->createToken('API Token')->plainTextToken;

        $output['res'] = 'success';
        $output['msg'] = 'User registered successfully.';
        $output['data'] = [
            'user_id' => $user->id,
            'user_name' => $user->first_name,
            'user_email' => $user->email,
            'token' => $token,
        ];
        return response()->json($output, 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $output['res'] = 'error';
            $output['msg'] = $validator->errors()->all();
            $output['data'] = [];
            return response()->json($output, 400);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->status != 'false') {
                // Create token
                $token = $user->createToken('API Token')->plainTextToken;

                $output['res'] = 'success';
                $output['msg'] = 'User logged in successfully.';
                $output['data'] = [
                    'user_id' => $user->id,
                    'user_name' => $user->first_name,
                    'user_email' => $user->email,
                    'cart_item' => Cart::where('user_id', $user->id)->where('status', 'true')->count(),
                    'token' => $token,
                ];
                if($user->login_count == 0)
                {
                  Mail::to($user->email)->send(new WelcomeEmail($user));
                }
                $update_count = User::find($user->id);
                $update_count->login_count = $user->login_count + 1;
                $update_count->save();
                return response()->json($output, 200);
            } else {
                $output['res'] = 'error';
                $output['msg'] = 'Unauthorized,please wait for verification';
                $output['data'] = [];
                return response()->json($output, 401);
            }
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Email or password not match';
            $output['data'] = [];
            return response()->json($output, 401);
        }
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'email' => 'required|valid_email',
        ];
        $messages = [
            'email.required' => 'Email id is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $query = User::where('email', $request->email);
            if ($query->exists()) {
                $user = $query->first();
                //send email to user for reset password
            } else {
                $output['res'] = 'error';
                $output['msg'] = 'Please provide valid email.';
                $output['data'] = [];
                return response()->json($output, 200);
            }
        }
    }
}
