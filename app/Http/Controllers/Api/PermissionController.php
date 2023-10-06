<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\{User};


class PermissionController extends Controller
{
    public function assignPermission(Request $request) {
        try {
            $users = User::find($request->user_id);
            $users->givePermissionTo($request->permission);  
            return response()->json(['status' => true, 'message' => "Assign Permission" ], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }

    } 

    public function revokePermission(Request $request) {
        try {
            $users = User::find($request->user_id);
            $users->revokePermissionTo($request->permission);
            return response()->json(['status' => true, 'message' => "Revok Permission" ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage() ], 500);
        }

    } 
}
