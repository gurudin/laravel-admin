<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Gurudin\LaravelAdmin\Models\Menu;

class MenuController extends Controller
{
    use ValidatesRequests;

    /**
     * Menu list.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin::menu.index');
    }

    /**
     * Menu save.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function save(Request $request, int $id = 0)
    {
        if ($id == 0) {
            $menu = new Menu;
        } else {
            $menu = (new Menu)->getMenu($id);
        }

        return view('admin::menu.save', compact('menu'));
    }
}