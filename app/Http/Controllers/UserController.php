<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Portal;
use App\Models\UserAccess;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Create User
     *
     * @param  Request  $request
     * @return Response
     */
    public function CreateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'portals' => 'array', // Corrected: Make sure it's an array
            'portals.*' => 'exists:portals,id', // Corrected: Validate existence of each portal ID
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role = 'user';
        $user->category_id = $request->input('category_id');

        $user->save();

        $portals = $request->input('portals', []);
        if (is_array($portals)) {
            foreach ($portals as $portalId) {
                $portal = Portal::find($portalId);
                if ($portal && $portal->category_id == $user->category_id) {
                    $userAccess = new UserAccess();
                    $userAccess->user_id = $user->id;
                    $userAccess->portal_id = $portalId;
                    $userAccess->save();
                }
            }
        }

        return response()->json([
            'message' => 'User created successfully.'
        ], 201);
    }
    
     /**
     * Get All User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAllUser(){
        $user = User::all();

        return response()->json([
            'message' => 'Successfully get all user',
            'user' => $user
        ], 200);
    }
    /**
     * Get User By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetUserById($id){
        $user = User::findOrFail($id);

        return response()->json([
            'message' => 'Successfully get user by id',
            'user' => $user
        ], 200);
    }
    /**
     * Update User By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateUserById(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:8',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->category_id = $request->input('division');
    
        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }
    
        UserAccess::where('user_id', $id)->delete();
    
        if ($user->role === 'user') {
            $portals = $request->input('portals', []);
            if (is_array($portals)) {
                foreach ($portals as $portalId) {
                    $userAccess =  new UserAccess();
                    $userAccess->user_id = $user->id;
                    $userAccess->portal_id = $portalId;
                    $userAccess->save();
                }
            }
        } else {
            $user->category_id = 1;
            $portals = Portal::orderBy('name', 'asc')->get();
            foreach ($portals as $portal) {
                $userAccess = new UserAccess();
                $userAccess->user_id = $user->id;
                $userAccess->portal_id = $portal->id;
                $userAccess->save();
            }
        }
    
        $user->save();
    
        return response()->json([
            'message' => 'User updated successfully.',
            'user' => $user
        ], 200);
    }
    
    /**
     * Delete User By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteUserById($id){
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'message' => 'Successfully delete user by id',
            'user' => $user
        ], 200);
    }
}
