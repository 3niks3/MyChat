<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Events\Test;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return view('profile');
    }

    public function profileEdit()
    {
        $user = auth()->user();
        return view('profile_edit', ['user' => $user]);
    }

    public function profileEditReceiver()
    {
        $user = auth()->user();
        $validator = \Validator::make(request()->all(), [
            'username' => ['required','min:3','max:240', Rule::unique('users')->ignore($user->username, 'username')],
            'name' => 'required|min:3|max:240|',
            'surname' => 'nullable|min:3|max:240|',
            'avatar' => ['nullable',new \App\Rules\ImageBase64()],
        ]);

        if($validator->fails())
        {
            $message = $validator->messages()->getMessages();
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $user->username = trim(request()->username);
        $user->name = trim(request()->name);
        $user->surname = trim(request()->surname);
        $user->save();

        //store file
        if( request()->has('avatar') && !empty(request()->avatar) && !empty(\Illuminate\Filesystem\Filesystem::formatClearBase64String(request()->avatar)) )
        {
            $avatar_base64 = \Illuminate\Filesystem\Filesystem::formatClearBase64String(request()->avatar);
            $file_extension = explode('/', mime_content_type(request()->avatar))[1];

            $user->storeUpdateAvatar($avatar_base64, $file_extension);
        }

        \Alert::success('Profile has been updated')->flash();
        return response()->json(['status' => true, 'messages' => [], 'target' => route('profile')]);
    }

    public function passwordChange()
    {
        return view('profile_change_password');
    }

    public function passwordChangeReceiver()
    {
        $user = auth()->user();
        $current_password = request()->current_password??'';

        if ( !Hash::check($current_password, $user->password))
        {
            $message = ['current_password' => ['Current password is incorrect.']];
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $validator = \Validator::make(request()->all(), [
            'new_password' => 'required|min:6|max:32|',
            'new_password_again' => 'required_with:new_password|same:new_password',
        ]);

        if($validator->fails())
        {
            $message = $validator->messages()->getMessages();
            return response()->json(['status' => false, 'messages' => $message]);
        }

        $user->password = Hash::make(request()->new_password);
        $user->save();

        \Alert::success('Password has been changed')->flash();

        return response()->json(['status' => true, 'messages' => [], 'target' => route('profile')]);
    }

}
