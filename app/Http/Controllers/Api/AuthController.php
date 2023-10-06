<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use App\Models\{User};
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request , $roleType) {
        try {
            $rules = ['email' => 'required|unique:users', 'password' => 'required' , 'name' => 'required'];
            $validator = Validator::make($request->all() , $rules, 
                        $messages = [
                            'email.required' => 'Username or Email is required',
                            'email.required' => 'Username or Email is already Use',
                            'password.required' => 'Password is required',
                            'name.required' => 'Name is required'
                        ]
                    );
            if ($validator->fails())
            {
                return response()
                    ->json(['status' => false, 'message' => $validator->errors() ], 403);
            }
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => bcrypt($request->password),
                "visible_password" => $request->password
            ]);
            $role = Role::where('name' , $roleType)->first();
            if($roleType == "author") {
                $permissions = Permission::all();
                $user->givePermissionTo($permissions);
            }
            $user->assignRole($role);
            return response()->json(['status' => true, 'data' =>$user,'message'=>'SuccessFully Created New '.$roleType], 200);

        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }

    }

    public function login(Request $request) {
        try {
            $rules = ['email' => 'required', 'password' => 'required'];
            $validator = Validator::make($request->all() , $rules, 
                        $messages = [
                            'email.required' => 'Username or Email is required',
                            'password.required' => 'password is required'
                        ]
                    );
            if ($validator->fails())
            {
                return response()
                    ->json(['status' => false, 'message' => $validator->errors() ], 403);
            }
            if (!auth()->attempt(array('email' => $request->email,'password' => $request->password)))
            {
                return response()->json(['status' => false, 'message' => 'Invalid Credentials'], 400);
            }
            $accessToken = auth()->user()
                ->createToken('authToken')->accessToken;
            $user = auth()->user();
            $user_data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'access_token' => $accessToken,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name')
            ];
            return response()->json(['status' => true, 'data' => $user_data, 'message' => "Login Successfully"], 200);
        } catch(\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }

    public function logout(){
        try
            {
                $auth_user = Auth::user()->token();
                $auth_user->revoke();
                return response()->json(['status' => true, 'message' => "Logout Successfully"], 200);
            }
        catch(\Exception $e)
        {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }
    }
}
