<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiDocsController extends Controller
{
    public function index()
    {
        return view('api-docs');
    }
    
    public function spec()
    {
        $path = public_path('openapi.json');
        
        if (!file_exists($path)) {
            abort(404, 'OpenAPI specification not found');
        }
        
        return response()->file($path, [
            'Content-Type' => 'application/json',
        ]);
    }
}
