<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\Menu;

class MenuController extends Controller
{
    /**
     * (view) Menu list.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\Menu $menu
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, Menu $menu)
    {
        $list = $menu::orderBy('id', 'asc')->get();
        
        return view('admin::menu.index', compact('list'));
    }

    /**
     * (view) Menu save.
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
            $menu = Menu::where('id', $id)->first();
        }

        return view('admin::menu.save', compact('menu'));
    }

    /**
     * (post) Menu create.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\Menu $menu
     *
     * @return Json
     */
    public function create(Request $request, Menu $menu)
    {
        return $menu::create($request->all())
            ? $this->response(true)
            : $this->response(false, 'Failed to create.');
    }
}