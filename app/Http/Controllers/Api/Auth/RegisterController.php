<?php
namespace App\Http\Controllers\Api\Auth;

use Exception;
use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public $select;
    public function __construct()
    {
        $this->select = ['id', 'name', 'email', 'otp', 'avatar'];
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|string|email|max:150|unique:users',
            'password'   => 'required|string|min:6|confirmed'
        ]);

        try {

            $user = User::create([
                'name'               => $request->input('name'),
                'email'              => strtolower($request->input('email')),
                'password'           => Hash::make($request->input('password')),
                'status'             => 'inactive',
            ]);

            return response()->json([
                'status'     => true,
                'message'    => 'User register in successfully.',
                'code'       => 200,
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'data'       => $user
            ], 200);

        } catch (Exception $e) {
            return Helper::jsonErrorResponse('User registration failed', 500, [$e->getMessage()]);
        }
    }
}
