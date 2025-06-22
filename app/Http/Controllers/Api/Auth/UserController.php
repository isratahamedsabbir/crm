<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public $select;
    public function __construct()
    {
        $this->select = ['id', 'name', 'email', 'avatar'];   
    }

    public function me()
    {
        $user = auth('api')->user();   
        $data = User::select($this->select)->find($user->id);     
        return Helper::jsonResponse(true, 'User details fetched successfully', 200, $data);
    }

    public function destroy()
    {
        $user = User::findOrFail(auth('api')->id());
        if (!empty($user->avatar) && file_exists(public_path($user->avatar))) {
            Helper::fileDelete(public_path($user->avatar));
        }
        Auth::logout('api');
        $user->forceDelete();
        return Helper::jsonResponse(true, 'Profile deleted successfully', 200);
    }
    
}
