<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhotoController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = [
            'query' => $request->query('query'),
            'per_page' => 14,
            'page' => 1,
        ];

        $response = Http::withToken(config('pexels.api_key'))->get(config('pexels.base_url').http_build_query($query))->json();

        return response()->json($response);
    }
}
