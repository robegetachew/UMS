<?php

namespace App\Http\Controllers\UserAuthController;
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:3,1')->only('verify','resend');
    }

    //notification
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail() ? 
        profile() : response()->json([
            'message' => ' Verify your email',
        ]);

    }

    //verify
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()
            ->json([
                'message' => 'verified',
            ]);
    }

    //resend
    public function resend(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return response()
        ->json(['message' => 'Verification link has been sent to your email address.',]);
    }
}
