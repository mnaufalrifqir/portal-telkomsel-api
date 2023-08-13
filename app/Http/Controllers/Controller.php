<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    // Hello World
    public function HelloWorld(){
        return response()->json('Hello World');
    }

    public function getImage($filename)
    {
        $path = 'images/' . $filename;
    
        if (file_exists($path)) {
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'Image not found'
            ], 404);
        }
    }

    public function getFile($filename)
    {
        $path = 'forms/' . $filename;
    
        if (file_exists($path)) {
            return response()->file($path);
        } else {
            return response()->json([
                'message' => 'File not found'
            ], 404);
        }
    }
}
