<?php
namespace Gurudin\LaravelAdmin\Controllers;

use Illuminate\Http\Request;
use Gurudin\LaravelAdmin\Models\AuthItem;

class RouteController extends Controller
{
    /**
     * Routes list.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Gurudin\Admin\Models\AuthItem $authItem
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request, AuthItem $authItem)
    {
        $local_routes = [];
        $local = app()->routes->getRoutes();
        foreach ($local as $route) {
            foreach ($route->methods as $method) {
                if (strtolower($method) != 'head') {
                    $local_routes[] = [
                        'name'   => $route->uri[0] == '/' ? $route->uri : '/' . $route->uri,
                        'method' => strtolower($method),
                    ];
                }
            }
        }
        unset($route);
        $local_routes = array_values($local_routes);

        $routes = $authItem->getRoutes();

        return view('admin::route.index', compact('routes', 'local_routes'));
    }

    /**
     * (post ajax) Routes create.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, AuthItem $authItem)
    {
        $data = [];
        foreach ($request->all() as $req) {
            $data[] = [
                'name'   => $req['name'],
                'method' => $req['method'],
                'type'   => $authItem::TYPE_PERMISSION
            ];
        }

        return $authItem->insert($data)
            ? $this->response(true)
            : $this->response(false, 'failed to create.');
    }

    /**
     * (delete ajax) Routes remove.
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function destroy(Request $request, AuthItem $authItem)
    {
        foreach ($request->all() as $data) {
            $authItem->removeItem($data, $authItem::TYPE_PERMISSION);
        }

        return $this->response(true);
    }
}