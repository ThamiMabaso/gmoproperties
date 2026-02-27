<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    /**
     * Display the objectives page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('objective');
    }
}
