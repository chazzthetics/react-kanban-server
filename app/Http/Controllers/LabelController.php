<?php

namespace App\Http\Controllers;

use App\Label;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::all();

        return response()->json($labels);
    }
}
