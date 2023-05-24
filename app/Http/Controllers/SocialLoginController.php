<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;
use Socialite;
use Storage;

class SocialLoginController extends Controller
{
     ### social login api
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        if($request->error!='')
            return redirect('login');
        
        $userSocial = Socialite::driver($provider)->user();
        if($userSocial->email != null)
            $user = User::where('email', $userSocial->email)->first();
        else
            $user = User::where('provider_id', $userSocial->id)->first();

        if (!$user)
        {
            //create new user and login him
            $fileContents = file_get_contents($userSocial->getAvatar());
            $filename = str_random(40);
            Storage::put('profile_pics/'.$filename.'.jpg', $fileContents);

            $user = User::updateOrCreate(['name'=>$userSocial->name, 'email'=>$userSocial->email, 'email_verified_at'=>date('Y-m-d'), 'provider'=>$provider, 'provider_id'=>$userSocial->id, 'profile_pic'=> 'profile_pics/'.$filename.'.jpg']);  
        }
        elseif($user->email_verified_at==null)
        {
            $user->update(['email_verified_at'=>date('Y-m-d')]);
        }
        Auth::login($user);
        return redirect('/');
    }

    public function logout()
    {       
        Auth::logout(); 
        return redirect('/');
    }
}
