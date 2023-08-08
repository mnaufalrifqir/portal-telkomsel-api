<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Create Category
     *
     * @param  Request  $request
     * @return Response
     */
    public function CreateCategory(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $category = Category::create([
            'name' => $request->name,
        ]);

        return response()->json([
            'message' => 'Successfully created category',
            'category' => $category
        ], 201);
    }

     /**
     * Get All Category
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetAllCategory(){
        $category = Category::all();

        return response()->json([
            'message' => 'Successfully get all category',
            'category' => $category
        ], 200);
    }

    /**
     * Get Category By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function GetCategoryById($id){
        $category = Category::findOrFail($id);

        return response()->json([
            'message' => 'Successfully get category by id',
            'category' => $category
        ], 200);
    }

    /**
     * Update Category By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateCategoryById(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'name' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'message' => 'Successfully update category by id',
            'category' => $category
        ], 200);
    }

    /**
     * Delete Category By Id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteCategoryById($id){
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Successfully delete category by id',
            'category' => $category
        ], 200);
    }
}
