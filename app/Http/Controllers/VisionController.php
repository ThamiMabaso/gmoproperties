<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisionController extends Controller
{
    /**
     * Display the vision and mission page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('vision');
    }
}
