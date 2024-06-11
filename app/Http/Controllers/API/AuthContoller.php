<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthContoller extends Controller
{
    public function login(Request $request)
    {
        try {
            //validate
            $this->validate($request, [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // cek credentials (login)
            $credentials = request(['email', 'password']);
            if (!Auth::attempt([
                'email' => $credentials['email'],
                'password' => $credentials['password']
            ])) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 401);
            };

            // cek jika password tidak sesuai
            $user = User::where('email', $credentials['email'])->first();
            if (!Hash::check(
                $request->password,
                $user->password,
                []
            )) {
                throw new \Exception(
                    'Invalid Credentials'
                );
            }

            // jika berhasil cek password maka loginkan
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated', 200);
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        try {
            //validate
            $this->validate($request, [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'confirm_password' => 'required|string|min:6'
            ]);

            // cek kondisi password dan confirm password
            if ($request->password != $request->confirm_password) {
                return ResponseFormatter::error([
                    'message' => 'Password not match'
                ], 'Authentication Failed', 401);
            }

            //create akun
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // get data akun
            $user = User::where('email', $request->email)->first();

            // create token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated', 200);
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function updatePassword(Request $request)
    {
        try {
            //validate
            $this->validate($request, [
                'old_password' => 'required',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6'
            ]);

            // get data user
            $user = Auth::user();

            // cek password lama
            if (!Hash::check($request->old_password, $user->password)) {
                return ResponseFormatter::error([
                    'message' => 'Password lama tidak sesuai'
                ], 'Authentication Failed', 401);
            }

            // cek password baru dan konfirmasi password baru
            if ($request->new_password != $request->confirm_password) {
                return ResponseFormatter::error([
                    'message' => 'Password tidak sesuai'
                ], 'Authentication Failed', 401);
            }

            // update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            return ResponseFormatter::success([
                'message' => 'Password berhasil diubah'
            ], 'Authenticated', 200);
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function allUsers()
    {
        $users = User::where('role', 'user')->get();
        return ResponseFormatter::success(
            $users,
            'Data user berhasil diambil'
        );
    }

    public function storeProfile(Request $request)
    {
        try {
            //validate
            $this->validate($request, [
                'first_name' => 'required',
                'image' => 'required|image|max:2048|mimes:jpg,jpeg,png'
            ]);

            // get data user
            $user = auth()->user();

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/profile', $image->hashName());

            // create profile
            $user->profile()->create([
                'first_name' => $request->first_name,
                'image' => $image->hashName()
            ]);

            // get data profile
            $profile = $user->profile;

            return ResponseFormatter::success(
                $profile, 'Profile berhasil diupdate'
            );

        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }
    
    public function updateProfile(Request $request){
        try {
            //validate
            $this->validate($request, [
                'first_name' => 'required',
                'image' => 'image|max:2048|mimes:jpg,jpeg,png'
            ]);

            // get data user
            $user = auth()->user();

            // cek jika user belum ada profile maka harus membuat profile terlebih dahulu
            if(!$user->profile){
                return ResponseFormatter::error([
                    'message' => 'Profile not found'
                ], 'Authentication Failed', 404);
            }

            //upload image
            if($request->file('image')){
                $image = $request->file('image');
                $image->storeAs('public/profile', $image->hashName());
                $user->profile()->update([
                    'first_name' => $request->first_name,
                    'image' => $image->hashName()
                ]);
            }else{
                $user->profile()->update([
                    'first_name' => $request->first_name
                ]);
            }

            // get data profile
            $profile = $user->profile;

            return ResponseFormatter::success(
                $profile, 'Profile berhasil diupdate'
            );
            
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

}
