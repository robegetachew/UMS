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
use Spatie\Activitylog\Models\Activity;

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
        activity()->log('Registered');

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
        activity()->log('Logged In');

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
        activity()->log('Updated profile');

        //validate
        $request->validate([
            'name' => 'unique:users,name|min:4',
            'email' => 'email|unique:users,email',
            'password' => 'min:8|confirmed'
        ]);
        //update
        $user = Auth::user();
        User::where('id','=',$user->id)->update([
            'name' => isset($request->name) ? :  $request->name ,
            'email' => isset($request->email) ? :  $request->email,
            'password' => Hash::check($request->password,$user->password) ? :  Hash::make($request->password)
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
        activity()->log('Profile viewed');

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
        activity()->log('Logged Out');

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
        activity()->log('Reseted password');

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

    //activity tracker
    public function activity()
    {
        $user_id = Auth::user()->id;
        $activity = Activity::where('causer_id','=',$user_id)->get()[0];
        return response()->json([
            'last activity' => $activity->description,
            'date' => $activity->created_at,
            User::all()->except(Auth::id())
        ]);
    }

    //activity tracker for admin
    public function all_activity()
    {
        $users = User::all()->except(auth()->user()->id);
        foreach($users as $user){
            $activity = Activity::where('causer_id','=',$user->id)->get();
            return response()->json([
                'name' => $user->name,
                'email' => $user->email,
                'status' => 'on progress',
                'role' => $user->hasRole('admin')? 'admin': 'user',
                'date' => Carbon::parse($activity->get('created_at'))->diffForHumans(),
                'activity' => $activity->get('description') ,
            ]);
        }
    }
}
