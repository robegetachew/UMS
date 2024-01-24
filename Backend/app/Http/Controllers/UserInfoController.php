<?php

namespace App\Http\Controllers;
use App\Models\UserInfo;
use App\Models\User;
use Carbon\Carbon; 
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class UserInfoController extends Controller
{
    //
    public function info()
    {
        $user = UserInfo::where('user_id','=',Auth::user()->id)->get();
        return response()->json([
            'data' => $user,
        ]);
        
    }

    public function store(Request $request){
        
        activity()->log('stored');

        $registerUserData = $request->validate([
            'full_name'=>'required|string',
            'gender'=>'required|string',
            'phone_number'=>'required',
            'date_of_birth'=>'required',
            'location'=>'required',
            'image_path' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',

        ]);
        $image_path = $request->file('image_path')->store('image_path', 'public');

        $user = UserInfo::create([
            'user_id' => Auth::user()->id,
            'full_name' => $registerUserData['full_name'],
            'gender' => $registerUserData['gender'],
            'phone_number' => $registerUserData['phone_number'],
            'date_of_birth' => Carbon::parse($registerUserData['date_of_birth']),
            'location' => $registerUserData['location'],
            'image_path' => $image_path,
        ]);

        event(new Registered($user));
        
        
        return response()->json([
            'message' => 'User info updated',
        ]);
        
    }
}
