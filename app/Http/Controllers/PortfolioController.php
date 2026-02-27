<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    /**
     * Display the portfolio page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('portfolio');
    }
}
