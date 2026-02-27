<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display the projects page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('project');
    }
}
