<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Carbon\Carbon; 
use Illuminate\Http\RedirectResponse;
use Mail;
use DB;

class UserAuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('throttle:3,1')->only('verify','resend');
        
        
    }
    //Register new user
    public function register(Request $request){
        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8'
        ]);
        $user = User::create([
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        event(new Registered($user));

        $cr = $request->only('email','password');
        Auth::attempt($cr);
        
        return response()->json([
            'message' => 'User verify ur email. Verification link sent',
        ]);
        return redirect()->route('verification.notice');
    }

    //Login
    public function login(Request $request){
        $loginUserData = $request->validate([
            'email'=>'required|string|email',
            'password'=>'required|min:8'
        ]);
        
        $user = User::where('email',$loginUserData['email'])->first();
        if(!$user || !Hash::check($loginUserData['password'],$user->password)){
            return response()->json([
                'message' => 'Invalid Credentials'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        if($user->hasRole('admin')){
                $role = 'Admin';
            }
            else {
                $role = 'User';
            }
        return response()->json([
            'status' => "Logged In",
            'access_token' => $token,
            'role' => $role,
        ]);
    }

    //search information
    public function show($id)
    {
        $data = User::find($id);
        return response()->json([
            'status' => '200',
            'data' => $data
        ]);
    }

    //updated information
    public function update(Request $request, $id)
    {
        //validate
        $request->validate([
            'name' => 'unique:users,name|min:4',
            'email' => 'email|unique:users,email',
            'password' => 'min:8|confirmed'
        ]);
        //update
        $user = auth()->user();
        User::where('id','=',$user->id)->update([
            isset($request->name) ? : $request->name ,
            isset($request->email) ? : $request->email,
            Hash::check($request->password,$user->password) ? : Hash::make($request->password)
        ]);

        //response
        return response()->json([
            'status' => '200',
            'message' => 'User information updated succesfully',
        ]);
    }

    //profile
    public function profile()
    {
        //return profile
        $user = Auth::user();
        if( $user->hasRole('admin'))
        {
            $role = "ADMIN";
        }
        else{
            $role = "User";
        }
        return response()->json([
            'status' => '200',
            'message' => 'profile',
            'data' => auth()->user(),
            'role' => $role,
        ]);
    }

    //delete profile
    public function destroy($id)
    {
        User::where('id',$id)->delete();
        return response()->json([
            "message"=>"User Deleted"
          ]);
    }

    //logout
    public function logout(){
        auth()->user()->tokens()->delete();
    
        return response()->json([
          "message"=>"logged out"
        ]);
    }

    //password forget form
    public function passwordForm()
      {
         return response()->json([
            'message' => 'this is reset view',
         ]);
      }
  
    //submit the form
      public function submitForm(Request $request)
      {
          $request->validate([
              'email' => 'required|email|exists:users',
          ]);
          
            $status = Password::sendResetLink(
                $request->only('email')
            );
            return $status === Password::RESET_LINK_SENT
                ? response()->json(['status' => __($status)])
                : response()->json(['email' => __($status)]);

          
      }
     
      //Reset form
      public function resetForm($token)
      { 
        return response()->json( ['token' => $token]);
    }
  
     // submit reset form
      public function submitReset(Request $request)
      {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : response()->json(['email' => [__($status)]]);
          
      }
    
}
