<?php

namespace App\Http\Controllers\UserAuthController;

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
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
        return redirect()->route('profile')->response()
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
