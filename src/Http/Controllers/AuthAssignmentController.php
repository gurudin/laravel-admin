<?php

namespace Gurudin\Admin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Models\AuthItemChild;

class AuthAssignmentController extends Controller
{
    /**
     * (view) Assignment index
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Support\Helper $helper
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Helper $helper)
    {
        $user_item = $helper::getAuthUser(Auth::user(), $request->get('group'));
        
        return view('admin::assignment.index', compact('user_item'));
    }

}
