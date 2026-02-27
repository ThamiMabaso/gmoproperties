<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThankYouController extends Controller
{
    /**
     * Display the thank you page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('thank-you');
    }
}
