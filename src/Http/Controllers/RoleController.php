<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Models\AuthItemChild;

class RoleController extends Controller
{
    /**
     * (view) Role list.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $roles = $authItem->getRole();

        return view('admin::role.index', compact('roles'));
    }

    /**
     * (view) Role view
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param string $name
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request, AuthItem $authItem, AuthItemChild $authItemChild, string $name)
    {
        $items['permissions'] = $authItem->getPermission();
        $items['roles']       = $authItem->getRole();
        $itemChildren         = $authItemChild->getAuthItemChild($name);
        
        return view('admin::role.view', compact('name', 'items', 'itemChildren'));
    }

    /**
     * (post ajax) Role create
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function create(Request $request, AuthItem $authItem)
    {
        $count = $authItem->where([
            'name' => $request->all()['name'],
            'method' => ($request->all()['method'] ? $request->all()['method'] : '')
        ])->count();

        if ($count > 0) {
            return $this->response(false, 'The name of the repeated.');
        }

        return $authItem->setItem(['type' => 'create', 'new' => $request->all()])
            ? $this->response(true)
            : $this->response(false);
    }
    /**
     * (put ajax) Role update
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function update(Request $request, AuthItem $authItem)
    {
        return $authItem->setItem(array_merge(['type' => 'update'], $request->all()))
            ? $this->response(true)
            : $this->response(false);
    }
    /**
     * (delete ajax) Role destroy
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function destroy(Request $request, AuthItem $authItem)
    {
        return $authItem->removeItem($request->all())
            ? $this->response(true)
            : $this->response(false);
    }
}
