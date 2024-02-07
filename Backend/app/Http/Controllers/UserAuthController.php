<?php

namespace App\Http\Controllers;
    
use App\Models\User;
use App\Models\UserInfo;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
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
        $this->middleware('auth:api', ['except' => ['login', 'register','passwordForm','submitForm','resetForm','submitReset']]);
        $this->middleware('throttle:3,1')->only('verify','resend');
    }     
    //Register new user
    public function register(Request $request){
        function random_id(){
            $number = "";
            for($i=0; $i<19; $i++) {
              $min = ($i == 0) ? 1:0;
              $number .= mt_rand($min,9);
            }
            return $number;
          }
        activity()->log('Registered');

        $registerUserData = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email|unique:users',
            'password'=>'required|min:8',
            'full_name'=>'required|string',
            'gender'=>'required|string',
            'phone_number'=>'required',
            'date_of_birth'=>'required',
            'location'=>'required',
            'image_path' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);
        $image_path = $request->file('image_path')->store('image_path', 'public');

        $user = User::create([
            'id' => random_id(),
            'name' => $registerUserData['name'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
            'full_name' => $registerUserData['full_name'],
            'gender' => $registerUserData['gender'],
            'phone_number' => $registerUserData['phone_number'],
            'date_of_birth' => Carbon::parse($registerUserData['date_of_birth']),
            'location' => $registerUserData['location'],
            'image_path' => $image_path,
        ]);

        event(new Registered($user));
        $cr = $request->only('email','password');
        Auth::attempt($cr);
        
        return response()->json([
            'message' => 'User verify ur email. Verification link sent',
        ]);
        
    }

    //Login
    public function login(Request $request){
        activity()->log('Logged In');

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        
        $credentials = $request->only('email', 'password');
        $token = auth()->guard('api')->attempt($credentials);
        
        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = auth()->guard('api')->user();
        /*
        if ($user->hasVerifiedEmail())
        {
            return response()->json([
                'message' => 'Email not verified',
            ], 403);
        }*/
        if ($user->is_active == False){
            return response()->json([
                'message' => 'your accounte is deactivated contact the administrator',
            ]);
        }
        return response()->json([
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
                'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
            ]
        ],200);
        
    }

    //search information by name
    public function show($name)
    {
        $data = User::where('name','=',$name)->first();
        return response()->json([
            'status' => '200',
            'data' => $data
        ]);
    }

    //updated information
    public function update(Request $request)
    {
        activity()->log('Updated profile');

        //validate
        $data = $request->validate([
            'name' => 'unique:users,name|min:4',
            'email' => 'email|unique:users,email',
            'password' => 'min:8|confirmed',
            'full_name'=>'string',
            'gender'=>'string',
            'phone_number'=>'string',
            'date_of_birth'=>'date',
            'location'=>'string',
            'image_path' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);
        //update
        $user_data = User::find(auth()->user()->id);
        isset($request->name)? $user_data->name = $data['name']: $user_data->name ;
        isset($request->email)? $user_data->email = $data['email']: $user_data->email ;
        isset($request->password)? $user_data->password = $data['password']: $user_data->password ;
        isset($request->full_name)? $user_data->full_name = $data['full_name']: $user_data->full_name ;
        isset($request->gender)? $user_data->gender = $data['gender']: $user_data->gender ;
        isset($request->phone_number)? $user_data->phone_number = $data['phone_number']: $user_data->phone_number ;
        isset($request->date_of_birth)? $user_data->date_of_birth = $data['date_of_birth']: $user_data->date_of_birth ;
        isset($request->location)? $user_data->location = $data['location']: $user_data->location ;
        isset($request->image_path)? $user_data->image_path = $data['image_path']: $user_data->image_path ;
        $user_data->save();
       
        //response
        return response()->json([
            'status' => '200',
            'message' => 'User information updated succesfully',
        ]);

    }
    public function update_user(Request $request,$id)
    {
        activity()->log('Updated users profile');

        //validate
        $data = $request->validate([
            'name' => 'unique:users,name|min:4',
            'email' => 'email|unique:users,email',
            'password' => 'min:8|confirmed'
        ]);
        //update
        $user_data = User::find($id);
        isset($request->name)? $user_data->name = $data['name']: $user_data->name ;
        isset($request->email)? $user_data->email = $data['email']: $user_data->email ;
        isset($request->password)? $user_data->password = $data['password']: $user_data->password ;
        $user_data->save();
       
        //response
        return response()->json([
            'status' => '200',
            'message' => 'User information updated succesfully',
            'data' => $user_data,
        ]);

    }

    //profile
    public function profile()
    {
        activity()->log('Profile viewed');

        //return profile
        $user = Auth::user();

        return response()->json([
            'data' => $user,
            
        ]);

    }

    //delete profile
    public function destroy($id)
    {
        User::where('id',$id)->update(['is_deleted' => True])->delete();

        return response()->json([
            "message"=>"User Deleted"
          ]);
    }

    //activate profile
    public function activate($id)
    {
        User::where('id',$id)->update(['is_active' => True]);

        return response()->json([
            "message"=>"User Activated"
          ]);
    }

    //deactivate profile
    public function deactivate($id)
    {
        User::where('id',$id)->update(['is_active' => False]);

        return response()->json([
            "message"=>"User Deactivated"
          ]);
    }
 
    //logout
    public function logout(){
        activity()->log('Logged Out');

        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());
        if($removeToken){
            return response()->json([
              'message' => 'Successfully logged out',
            ]);
        }else{
              return response()->json([
                  'success' => false,
                  'message' => 'Failed logged out',
              ], 409);
        }
    }

    //referesh token
    public function refresh()
    {
        $token = auth()->guard('api')->refresh();
        if($token){
            return response()->json([
                'user' => auth()->guard('api')->user(),
                'authorization' => [
                    'token' => $token,
                    'type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ]);
        }else{
              return response()->json([
                  'success' => false,
                  'message' => 'Failed refresh token',
              ], 409);
        }
    
    }

    //password forget form
    public function passwordForm()
      {
         return response()->json([
            'message' => 'this is reset view',
         ]);
      }
  
    //submit the form forget password
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
      
      public function resetForm(Request $request, $token)
        {
            if (!$token) {
                return response()->json(['error' => 'Invalid token'], 400);
            }

            // Include a script to redirect to the React /reset-password page
            $script = "<script>window.location.href = 'http://localhost:3000/reset-password?token={$token}?';</script>";

            return response($script);
        }

  
     // submit reset form
      public function submitReset(Request $request)
      {
        activity()->log('Reseted password');

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
     
        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );
     
        return $status === Password::PASSWORD_RESET
                    ? response()->json('status: successfuly reseted')
                    : response()->json(['email' => [__($status)]]);
    
    }

    //activity tracker
    public function activity()
    {
        $user_id = Auth::user()->id;
        $activity = Activity::where('causer_id','=',$user_id)->get();
        $result = $activity;
        $data = json_decode($result, true);
        $list = array();
        foreach ($data as $item) {
            $description = $item['description'];
            $updatedDate = $item['updated_at'];
            $list[$description] = Carbon::parse($updatedDate)->diffForHumans();
        }
        $descriptions = json_encode($list,true);

        return response()->json([
            'last activity' => $list

        ]); 
    }

    //activity tracker for admin
    public function all_activity()
    {
        foreach(User::where('id','>',1)->get() as $user){
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
