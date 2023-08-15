<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Portal;
use App\Models\UserAccess;

class PortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Create Portal
     *
     * @param  Request  $request
     * @return Response
     */
    public function CreatePortal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|unique:portals|nullable|url',
            'description' => 'nullable|string',
            'formFile' => 'required|mimes:pdf',
            'formImg' => 'required|mimes:jpeg,jpg,png,svg',
            'category_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $data = $request->except(['formFile', 'formImg']);

        if ($request->hasFile('formFile')) {
            $file = $request->file('formFile');
            $path = $file->store('user-guide');
            $data['file_url'] = $path;
        }

        if ($request->hasFile('formImg')) {
            $file = $request->file('formImg');
            $path = $file->store('portal');
            $data['img_url'] = $path;
        }

        $portal = Portal::create([
            'name' => $data['name'],
            'link' => $data['link'],
            'description' => $data['description'],
            'file_url' => $data['file_url'],
            'img_url' => $data['img_url'],
            'category_id' => $data['category_id'],
        ]);

        return response()->json([
            'message' => 'Portal successfully created',
            'portal' => $portal
        ], 201);
    }
    
     /**
     * Get All Portal
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAllPortal(){
        $portal = Portal::all();
    
        return response()->json([
            'message' => 'Successfully get all portal',
            'portal' => $portal
        ], 200);
    }    

    /**
     * Get Portal By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetPortalById($id){
        $portal = Portal::findOrFail($id);

        return response()->json([
            'message' => 'Successfully get portal by id',
            'portal' => $portal
        ], 200);
    }

    /**
     * Update Portal By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdatePortalById(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|unique:portals|nullable|url',
            'description' => 'nullable|string',
            'formFile' => 'nullable|mimes:pdf',
            'formImg' => 'nullable|mimes:jpeg,jpg,png,svg',
            'category_id' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }
    
        $portal = Portal::findOrFail($id);
        $data = $request->except(['formFile', 'formImg']);
    
        if ($request->hasFile('formFile')) {
            if ($portal->file_url) {
                Storage::delete($portal->file_url);
            }
    
            $file = $request->file('formFile');
            $path = $file->store('public/user-guide');
            $data['file_url'] = $path;
        }
    
        if ($request->hasFile('formImg')) {
            if ($portal->img_url) {
                Storage::delete($portal->img_url);
            }
    
            $file = $request->file('formImg');
            $path = $file->store('public/portal');
            $data['img_url'] = $path;
        }
    
        $portal->category_id = $data['category_id'];
    
        $portal->update($data);
    
        return response()->json([
            'message' => 'Portal updated successfully',
            'portal' => $portal
        ], 200);
    }

    /**
     * Delete Portal By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeletePortalById($id){
        $portal = Portal::findOrFail($id);

        $portal->delete();

        return response()->json([
            'message' => 'Successfully delete portal by id',
            'portal' => $portal
        ], 200);
    }

    /**
     * Get Portal By Category Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetPortalByCategoryID($id){
        $portal = Portal::where('category_id', $id)->get();

        return response()->json([
            'message' => 'Successfully get portal by category id',
            'portal' => $portal
        ], 200);
    }

    /**
     * Get Portal By Category User Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetPortalByUserID($userID){
        $access = UserAccess::where('user_id', $userID)->get();
        $portal = [];
        foreach($access as $acc){
            $portal[] = Portal::where('id', $acc->portal_id)->get();
        }
        return response()->json([
            'message' => 'Successfully get portal by user id',
            'portal' => $portal
        ], 200);
    }
}
