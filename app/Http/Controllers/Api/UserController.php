<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showUser($id=null)
    {
        if(empty($id)){
            $user = User::get();
            return response()->json(['user'=>$user],200);
        }else{
            $user = User::find($id);
            return response()->json([
                'user'=> $user,
            ],200);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function addUser(Request $request)
    {
        if($request->ismethod('post')){
            $data = $request->all();

            //custome validation
            $rules = [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ];
            $errorMessage = [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'name.email' => 'Email must be a valid email',
                'password.required' => 'Password is required',
            ];
            $validator = Validator::make($data,$rules,$errorMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user = User::create($data);
            $message = 'User added successfully';
            return response()->json([
                'message' => $message,
                'user' => $user,
            ],201);

        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function addMultipleUser(Request $request)
    {
        if($request->ismethod('post')){
            $data = $request->all();
        }
        $rules = [
            'users.*.name' => 'required',
            'users.*.email' => 'required|email|unique:users',
            'users.*.password' => 'required',
        ];
        $errorMessage = [
            'users.*.name.required' => 'Name is required',
            'users.*.email.required' => 'Email is required',
            'users.*.name.email' => 'Email must be a valid email',
            'users.*.password.required' => 'Password is required',
        ];
        $validator = Validator::make($data,$rules,$errorMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }
        foreach($data['users'] as $mulUsers){
            $user = User::create($mulUsers);
        }
        $message = "All users added successfully";
        return response()->json([
            'message' => $message,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function updateUser(Request $request,$id)
    {
        if($request->ismethod('put')){
            $data = $request->all();
            $rules = [
                'name' => 'required',
                'password' => 'required',
            ];
            $errorMessage = [
                'name.required' => 'Name is required',
                'password.required' => 'Password is required',
            ];
            $validator = Validator::make($data,$rules,$errorMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user = User::findOrFail($id);
            $updateUser = $user->update($data);
            $message = "User updated successfully";
            return response()->json([
                'message' => $message,
            ],202);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function updateSingleRecord(Request $request, $id)
    {
        if($request->ismethod('patch')){
            $data = $request->all();
            $rules = [
                'name' => 'required',
            ];
            $errorMessage = [
                'name.required' => 'Name is required',
            ];
            $validator = Validator::make($data,$rules,$errorMessage);
            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }
            $user = User::findOrFail($id);
            $updateUser = $user->update($data);
            $message = "User single record successfully";
            return response()->json([
                'message' => $message,
            ],202);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id)->delete();
        $message = "User deleted successfully";
        return response()->json(['message'=> $message],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deleteMultipleUser($ids)
    {
        $ids = explode(',',$ids);
        $user = User::whereIn('id',$ids)->delete();
        $message = "Multiple user deleted successfully";
        return response()->json(['message'=> $message],200);
    }
    public function deleteUserJson(Request $request)
    {
        if($request->ismethod('delete')){
            $data = $request->all();
            $user = User::where('id',$data['id'])->delete();
            $message = "User deleted successfully with json";
            return response()->json(['message'=> $message],200);
        }
    }
    public function deleteMultipleUserJson(Request $request)
    {
        if($request->ismethod('delete')){
            $data = $request->all();
            $user = User::whereIn('id',$data['ids'])->delete();
            $message = "Multiple user deleted successfully with json";
            return response()->json(['message'=> $message],200);
        }
    }
    public function register(Request $request){
        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $user = User::create($validator);
        $token = $user->createToken('auth_token')->accessToken;
        $message = "User registered successfully";
        return response()->json([
            'token' => $token,
            'message' => $message,
            'user' => $user,
        ],201);
    }
    public function login(Request $request){
        $validator = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //$password = $request['password'];
        // $user = User::where(['email'=>$request['email'],'password'=>Hash::make(Input::get($password))])->first();
        if(Auth::attempt(['email'=>$request['email'], 'password'=>$request['password']])){
            $user = User::where(['email'=>$request['email']])->first();
            $token = $user->createToken('auth_token')->accessToken;
            $message = "User loged in successfully";
            return response()->json([
                'token' => $token,
                'message' => $message,
                'user' => $user,
            ],200);
        }else{
            $message = "Email or password incorrect";
            return response()->json([
                'message' => $message,
            ],422);
        }
    }
    public function userInfo($id){
        $user = User::findOrFail($id);
        $message = "User is Authorized";
        return response()->json([
            'message' => $message,
            'user' => $user,
        ],200);
    }
}
