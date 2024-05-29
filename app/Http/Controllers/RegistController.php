<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Hash;

use App\Models\usaha;
use App\Models\users;
use Illuminate\Validation\ValidationException;


use Illuminate\Support\Facades\Storage;

class RegistController extends BaseController
{
    //
    public function GetUsaha(Request $request)
    {
        $usaha = usaha::all();
        return response()->json($usaha);
    }
    public function GetUsers(Request $request)
    {
        $users = users::with(['usaha', 'roles'])->get();
        return response()->json($users);
    }

    public function RegistUsaha(Request $request)
    {
        try {
            $validateDataUsaha = $request->validate([
                'nama' => 'required|max:55',
                'kategori' => 'required',
                'alamat' => 'required',
            ]);

            $usaha = usaha::create($validateDataUsaha);

            $validateDataUsers = $request->validate([
                'nama_pengguna' => 'required|max:55', // Gunakan 'nama_pengguna' sebagai parameter untuk nama pengguna
                'email' => 'required|email|unique:users,email',
                'password' => 'required|max:12',
            ]);

            $hasedPassword = Hash::make($validateDataUsers['password']);
            $validateDataUsers['password'] = $hasedPassword;

            $validateDataUsers['nama'] = $validateDataUsers['nama_pengguna'];
            unset($validateDataUsers['nama_pengguna']);

            $validateDataUsers['usaha_id'] = $usaha->id;
            $validateDataUsers['role_id'] = 1;

            $users = users::create($validateDataUsers);

            $sucess['token'] = $users->createToken('auth_token')->plainTextToken;
            $sucess['nama'] = $users->nama;

            return response()->json(['status' => 200, "massage" => 'Berhasil Mendaftar', 'data' => $sucess]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors(), 'message' => 'Pastikan semua datanya terisi']);
        }
    }

    public function tambahPegawai(Request $request, $usaha_id)
    {
        try {
            $validateDataUsers = $request->validate([
                'nama_pengguna' => 'required|max:55', // Gunakan 'nama_pengguna' sebagai parameter untuk nama pengguna
                'email' => 'required|email|unique:users,email',
                'password' => 'required|max:12',
            ]);

            $usaha = usaha::where('id', $usaha_id)->first();

            $hasedPassword = Hash::make($validateDataUsers['password']);
            $validateDataUsers['password'] = $hasedPassword;
            $validateDataUsers['nama'] = $validateDataUsers['nama_pengguna'];
            unset($validateDataUsers['nama_pengguna']);

            $validateDataUsers['usaha_id'] = $usaha->id;
            $validateDataUsers['role_id'] = 2;

            $users = users::create($validateDataUsers);
            $sucess['token'] = $users->createToken('auth_token')->plainTextToken;
            $sucess['nama'] = $users->nama;

            return response()->json(['status' => 200, "massage" => 'Berhasil Mendaftar', 'data' => $sucess]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'errors' => $e->errors(),
                'message' => 'Pastikan semua datanya terisi'
            ]);
        }


    }

    public function ambilPegawai($usaha_id)
    {
        $pegawai = users::where('usaha_id', $usaha_id)->where('role_id', 2)->get();
        return response()->json(['status' => 200, 'data' => $pegawai]);
    }

    public function LoginUser(Request $request)
    {
        try {
            $login = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $user = users::where('email', $login['email'])->first();

            if (!$user || !Hash::check($login['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'error' => 'Email/Password Yang  Anda Masukkan Salah.',

                ]);
            }
            $userWithRelations = $user->load(['usaha', 'roles']);
            // $user = users::with(['usaha', 'roles'])->get();

            $sukses['token'] = $user->createToken('auth_token')->plainTextToken;


            // return response()->json(['status' => 200, 'token' => $user->createToken('auth_token')->plainTextToken, 'data' => $user]);
            return response()->json(['status' => 200, 'data' => [$sukses, $userWithRelations]]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 'error', 'message' => 'Email/Password yang dimasukkan salah', 'errors' => $e->errors()]);
        }
    }


    public function LogoutUser(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return response()->json(['status' => 200, 'data' => auth()->user()]);
        } catch (ValidationException $e) {
            return response()->json(["massage" => $e]);
        }
    }

    public function UpdateUser(Request $request, $user_id)
    {

        $user = users::where('id', $user_id)->first();

        $validate = $request->validate([
            'nama' => 'required',
            // 'gambar' => 'required'
        ]);

        // $image = null;

        // if ($request->gambar) {
        //     $gambar = $this->random_string();
        //     $extension = $request->gambar->extension();
        //     $imageName = $gambar . '.' . $extension;
        //     Storage::putFileAs('public/image/user' . $user_id, $request->gambar, $imageName);

        //     // Generate the URL to the stored image
        //     $image = Storage::url('public/image/user' . $user_id . '/' . $imageName);
        // }

        // $validate['gambar'] = $image;

        $user->nama = $validate['nama'];
        // $user->gambar = $validate['gambar'];

        $user->save();

        return response()->json(['status' => 200, 'resp' => 'Berhasil Mengubah profile']);
    }

    public function GetUsersByEmail($email)
    {
        $user = users::where('email', $email)->first();
        $userWithRelations = $user->load(['usaha', 'roles']);
        return response()->json(['status' => 200, 'data' => $userWithRelations]);
    }
    public function Users(Request $request)
    {
        return response()->json(auth()->user());
    }


    public function deleteUser($user_id, $usaha_id)
    {
        try {
            $user = users::where('id', $user_id)->first();

            // $user->delete();

            $alluser = users::where('usaha_id', $usaha_id)->all();

            return response()->json(['status' => 200, 'data' => $user, 'alluser' => $alluser]);
        } catch (ValidationException $e) {
            return response()->json(['status' => 500, 'message' => $e]);

        }

    }

    function random_string($length = 20)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
