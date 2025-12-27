<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function services()
    {
        return view('pages.services');
    }

    public function developers()
    {
        // Placeholder for next task
        return view('pages.developers');
    }

    public function pricing()
    {
        // Placeholder
        return view('pages.pricing');
    }

    public function support()
    {
        // Placeholder
        return view('pages.support');
    }
}
