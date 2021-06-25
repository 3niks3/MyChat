<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class HomeController extends Controller
{
    public function home()
    {
        return view('home');
    }
    public function registration()
    {
        return view('registration');
    }

    public function forgetPassword()
    {
        return view('forget_password');
    }


    public function registrationReceiver()
    {

        $validator = \Validator::make(request()->all(), [
            'username' => 'required|min:3|max:240|unique:users,username',
            'email' => 'required|email|min:3|max:240|unique:users,email',
            'name' => 'required|min:3|max:240|',
            'surname' => 'nullable|min:3|max:240|',
            'password' => 'required|min:6|max:32|',
            'password_again' => 'required_with:password|same:password',
            'avatar' => ['nullable',new \App\Rules\ImageBase64()],
        ]);

        if($validator->fails())
        {
            $response = [
                'status' => false,
                'messages' => $validator->messages()->getMessages(),
            ];

            return response()->json($response);
        }


        $user = new User;
        $user->name = trim(request()->name);
        $user->surname = trim(request()->surname);
        $user->username = trim(request()->username);
        $user->email = trim(request()->email);
        $user->password = Hash::make(request()->password);
        $user->save();

        Auth::login($user);

        //store file
        if( request()->has('avatar') && !empty(request()->avatar) && !empty(\Illuminate\Filesystem\Filesystem::formatClearBase64String(request()->avatar)) )
        {
            $avatar_binary = \Illuminate\Filesystem\Filesystem::formatClearBase64String(request()->avatar);
            $file_extension = explode('/', mime_content_type(request()->avatar))[1];

            $user->storeUpdateAvatar($avatar_binary, $file_extension);
        }

        $response = [
            'status' => true,
            'messages' => [],
            'target' => route('profile'),
        ];

        return response()->json($response);
    }

    public function forgetPasswordReceiver()
    {
        $validator = \Validator::make(request()->all(), [
            'email' => 'required|exists:users,email',
            'new_password' => 'required|min:6|max:32|',
            'new_password_again' => 'required_with:new_password|same:new_password',
        ]);

        if($validator->fails())
        {
            $message = $validator->messages()->getMessages();
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $user = User::where('email',trim(request()->email))->first();
        $user->password = Hash::make(request()->new_password);
        $user->save();

        \Alert::success('Password has been changed')->flash();
        return response()->json(['status' => true, 'messages' => [], 'target' => route('home')]);
    }

    public function login()
    {
        $status = true;
        $message = '';
        $target = null;
        $credentials = request()->only('email', 'password');

        if(!isset($credentials['email']) || !isset($credentials['password']) )
        {
            $status = false;
            $message = 'Incorrect authentication credentials';
        }

        if ($status && Auth::attempt($credentials)) {
            request()->session()->regenerate();
            $status = true;
            $target = route('profile');
        }else{
            $status = false;
            $message = 'Incorrect authentication credentials';
        }

        $response = [
            'status' => $status,
            'messages' => $message,
            'target' => $target,
        ];

        return response()->json($response);
    }

    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    }

}
