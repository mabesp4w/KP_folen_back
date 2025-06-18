<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\CrudResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function spartaValidation($request, $id = "")
    {
        $required = "";
        if ($id == "") {
            $required = "required";
        }
        $rules = [
            'email' => 'required|unique:users,email,' . $id,
        ];

        $messages = [
            'email.required' => 'Email harus diisi.',
            'email.unique' => 'Email sudah terdaftar.',
        ];
        $validator = Validator::make($request, $rules, $messages);

        if ($validator->fails()) {
            $message = [
                'judul' => 'Gagal',
                'type' => 'error',
                'message' => $validator->errors()->first(),
            ];
            return response()->json($message, 400);
        }
    }

    function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|max:255',
                'password' => 'required',
            ],
            [
                'email.required' => 'Email harus diisi',
                'password.required' => 'Password harus diisi',
                'email.email' => 'Email tidak valid',
                'email.max' => 'Email maksimal 255 karakter',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        // check email and password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Kombinasi email dan password salah',
            ], 401);
        }
        // mengambil email
        $user = User::where('email', $request['email'])->firstOrFail();
        // membuat token
        $token = $user->createToken('authToken');
        // add expires_at to token
        $token->token->expires_at = now()->addHours(10);
        $token->token->save();

        return response()->json([
            'status' => true,
            'role' => $user->role,
            'token' => $token->accessToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }

    function cekToken(Request $request)
    {
        $user = $request->user();

        // Validasi kedaluwarsa dari tabel oauth_access_tokens
        $token = $user->token();
        if ($token->expires_at < now()) {
            // $token->delete();
            return response()->json([
                'status' => false,
                'message' => 'Token telah kedaluwarsa silahkan login kembali',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'expires_at' => $token->expires_at,
            'now' => now(),
            'role' => $user->role,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
            ]
        ]);
    }

    function logout(Request $request)
    {
        $token = $request->user()->token(); // Akses token terlebih dahulu
        // Hapus token
        $token->delete();
        return response()->json([
            'status' => true,
            'message' => 'Logout Berhasil',
        ]);
    }

    function update($id, Request $request)
    {
        $data_req = $request->all();
        // return $data_req;
        $validate = $this->spartaValidation($data_req, $id);
        if ($validate) {
            return $validate;
        }
        $user = User::find($id);
        $user->update([
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'show_password' => $request->password,
        ]);
        return new CrudResource('success', 'Data Berhasil Diubah', $user);
    }
}
