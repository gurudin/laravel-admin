<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\Menu;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Support\Helper;

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
        foreach ($list as &$item) {
            foreach ($list as $val) {
                if ($item['parent'] == $val['id']) {
                    $item['parentName'] = $val['title'];
                }
            }
        }
        unset($item);
        
        return view('admin::menu.index', compact('list'));
    }

    /**
     * (view) Menu save.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function save(Request $request, AuthItem $authItem, int $id = 0)
    {
        if ($id == 0) {
            $menu = new Menu;
        } else {
            $menu = Menu::where('id', $id)->first();
        }

        $menu_list = Menu::get();
        foreach ($menu_list as &$item) {
            if ($item['id'] == $menu['parent']) {
                $menu['parentName'] = $item['title'];
            }
            foreach ($menu_list as $val) {
                if ($item['parent'] == $val['id']) {
                    $item['parentName'] = $val['title'];
                }
            }
        }
        unset($item);

        $routes = $authItem::where(['type' => 2, 'method' => 'get'])->get();

        return view('admin::menu.save', compact(
            'menu',
            'menu_list',
            'routes'
        ));
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

    /**
     * (put) Menu update.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\Menu $menu
     *
     * @return Json
     */
    public function update(Request $request, Menu $menu)
    {
        Helper::removeCache('menu');

        $input = $request->all();
        unset($input['parentName']);

        return $menu::where(['id' => $request->all()['id']])->update($input)
            ? $this->response(true)
            : $this->response(false, 'Failed to update.');
    }

    /**
     * (delete) Menu delete.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\LaravelAdmin\Models\Menu $menu
     *
     * @return Json
     */
    public function destroy(Request $request, Menu $menu)
    {
        Helper::removeCache('menu');

        return $menu::where(['id' => $request->all()['id']])->delete()
            ? $this->response(true)
            : $this->response(false, 'Failed to delete.');
    }
}