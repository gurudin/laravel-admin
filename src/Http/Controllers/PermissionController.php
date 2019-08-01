<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\AuthItem;
use Gurudin\LaravelAdmin\Models\AuthItemChild;
use Gurudin\LaravelAdmin\Support\Helper;

class PermissionController extends Controller
{
    /**
     * (view) Permission list.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $permissions = $authItem->getPermission();

        return view('admin::permission.index', compact('permissions'));
    }

    /**
     * (post ajax) Permission save.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function save(Request $request, AuthItem $authItem)
    {
        $data = $request->all();
        if ($data['type'] == 'create') {
            $count = $authItem->where([
                'name' => $data['new']['name'],
                'type' => $data['new']['type'],
                'method' => ($data['new']['method'] ? $data['new']['method'] : '')
            ])->count();

            if ($count > 0) {
                return $this->response(false, 'The name of the repeated.');
            }
        }

        return $authItem->setItem($request->all())
            ? $this->response(true)
            : $this->response(false, 'Failed to save.');
    }

    /**
     * (delete ajax) Routes remove.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return Json
     */
    public function destroy(Request $request, AuthItem $authItem)
    {
        Helper::removeCache('menu');

        return $authItem->removeItem($request->all())
            ? $this->response(true)
            : $this->response(false, 'Failed to delete.');
    }

    /**
     * (view) Permission view
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     * @param string $name
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view(Request $request, AuthItem $authItem, AuthItemChild $authItemChild, string $name)
    {
        $routes = $authItem->getRoutes();
        $child_items = $authItemChild->getAuthItemChild($name);

        return view('admin::permission.view', compact(
            'name',
            'routes',
            'child_items'
        ));
    }

    /**
     * (post ajax) Batch create route children
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     *
     * @return Json
     */
    public function batchCreateRouteChild(Request $request, AuthItemChild $authItemChild)
    {
        Helper::removeCache('menu');
        
        return $authItemChild->saveItemChild($request->all())
            ? $this->response(true)
            : $this->response(false, 'Failed to create.');
    }
    /**
     * (delete ajax) Batch remove route children
     *
     * @param Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItemChild $authItemChild
     *
     * @return Json
     */
    public function batchRemoveRouteChild(Request $request, AuthItemChild $authItemChild)
    {
        Helper::removeCache('menu');

        return $authItemChild->removeItemChild($request->all())
            ? $this->response(true)
            : $this->response(false, 'Failed to delete.');
    }
}