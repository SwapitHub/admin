<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request to send a password reset email.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'email' => 'required|email',
        ]);

        // Send the password reset link to the user's email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Check if the email was sent successfully and return an appropriate response
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['res'=>'success','msg' => __($status)], 200);
        }

        // If the email was not sent successfully, return an error response
        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}

