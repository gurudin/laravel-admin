<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;

class SiteController extends Controller
{
    use ValidatesRequests;

    /**
     * Login view.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function welcome(Request $request)
    {
        return view('admin::welcome');
    }
}