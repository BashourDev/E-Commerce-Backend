<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt([
            'email'=>$request->get('email'),
            'password'=>$request->get('password')
        ])){
            $token=Auth::user()->createToken('myToken'.Auth::user()->id)->plainTextToken;
            $user = Auth::user();
            return \response(['user'=> $user,'token'=>$token]);
        }

        else {
            return \response('error with the username or password', 401);
        }
    }

    public function logout()
    {
        return auth()->user()->tokens()->delete();
    }

    public function update(Request $request)
    {

//        $request->validate([
//            'username' => 'unique:users,username'
//        ]);

        $v = Validator::make($request->only(['email']), [
            'email' => [
                'required',
                Rule::unique('users')->ignore(auth()->user()->id),
            ],
        ]);

        $v->validate();

        if ($request->get('updatePassword')) {
            if (!Hash::check($request->get('oldPassword'), \auth()->user()->password)) {
                return response("password mismatch", 403);
            }
            \auth()->user()->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password'=> bcrypt($request->get('password')),
            ]);
        } else {
            \auth()->user()->update([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
            ]);

        }

        $user = User::query()->find(\auth()->user()->id);
        if (\auth()->user()->role !== User::ROLE_Admin) {
            return response($user->cast()->loadMissing('hospital'));
        } else {
            return response($user->cast());
        }

    }

    public function register(Request $request)
    {
        $entity = User::query()->where('email', $request->get('email'))->first();
        if ($entity) {
            return \response('email already exists', 403);
        }

        $entity = User::query()->create([
            'name' => $request->get('name'),
//            'slug' => Str::slug($request->get('name').' '.explode('@', $request->get('email'))[0]),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'address' => $request->get('address'),
            'phone' => $request->get('phone'),
            'isAdmin' => false
        ]);

        $entity->cart()->create([]);

        if (Auth::attempt([
            'email'=>$request->get('email'),
            'password'=>$request->get('password')
        ])){
            $token=Auth::user()->createToken('myToken'.Auth::user()->id)->plainTextToken;
            return \response(['user'=>Auth::user(),'token'=>$token]);

        } else {
            return \response('error with the email or password', 401);
        }

    }

    public function updatePassword(Request $request, User $user)
    {
        if (!Hash::check($request->get('oldPassword'), $user->password)) {
            return Response('wrong password', 422);
        }
        return $user->update($request->get('password'));
    }
}
