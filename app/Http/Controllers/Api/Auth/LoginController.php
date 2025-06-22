<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public $select;
    public function __construct()
    {
        $this->select = ['id', 'name', 'email', 'avatar', 'otp_verified_at'];   
    }

    public function Login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email|exists:users,email',
                'password' => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return Helper::jsonResponse(false, 'Validation failed', 422, $validator->errors());
            }

            $user = User::where('email', $request->email);

            if (!$user) {
                return Helper::jsonResponse(false, 'User not found', 404);
            }

            $user = $user->where('status', 'active')->first();

            if (!$user) {
                return Helper::jsonResponse(false, 'user is not active', 404);
            }

            //! Check the password
            if (!Hash::check($request->password, $user->password)) {
                return Helper::jsonResponse(false, 'Invalid password', 401);
            }

            //* Generate token if email is verified
            $token = auth('api')->login($user);

            return response()->json([
                'status'     => true,
                'message'    => 'Login successful',
                'code'       => 200,
                'token_type' => 'bearer',
                'token'      => $token,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data'       => $user,
            ], 200);

        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred during login.', 500, ['error' => $e->getMessage()]);
        }
    }

}
