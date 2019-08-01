<?php

namespace Gurudin\LaravelAdmin\Support;

use Illuminate\Support\Facades\Auth;
use Gurudin\LaravelAdmin\Models\Menu;
use Gurudin\LaravelAdmin\Models\AuthAssignment;
use Gurudin\LaravelAdmin\Models\AuthItemChild;
use Illuminate\Support\Facades\Cache;

class Helper
{
    /**
     * Is admin
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return in_array(Auth::user()->email, config('admin.admin_email')) ? true : false;
    }

    /**
     * Remove cache
     *
     * @param string $key='menu'
     *
     * @return void
     */
    public static function removeCache(string $key)
    {
        Cache::forget($key);
    }

    /**
     * Get menu.
     *
     * @param Gurudin\LaravelAdmin\Models\Menu $menu
     *
     * @return array
     */
    public static function getMenu()
    {
        if ($cache = Cache::get("menu")) {
            if (isset($cache['menu-' . Auth::user()->id])) {
                return $cache['menu-' . Auth::user()->id];
            }
        }

        if (self::isAdmin()) {
            $list = Menu::orderBy('order', 'desc')->get()->toArray();
        } else {
            $list = self::getMeusByUser(Auth::user()->id);
        }

        $menus = self::recursion($list);
        cache(['menu' => [
            'menu-' . Auth::user()->id => $menus
        ]], 60 * 24);

        return $menus;
    }

    /**
     * Is permission.
     *
     * @param string $user_id
     * @param array $route ( ['method' => 'get', 'route' => '/admin/route'] )
     *
     * @return bool
     */
    public static function isPermission(string $user_id, array $route)
    {
        $check = false;

        $routes = self::getRoutes($user_id);
        foreach ($routes as $value) {
            $method = implode(",", $route['method']);
            if (strcasecmp($value['child'], '/' . $route['route']) == 0
                && strpos(strtolower($method), strtolower($value['method'])) !== false
            ) {
                $check = true;
            }
        }

        if (!$check) {
            foreach (config('admin.allow') as $value) {
                if (strcasecmp($value['uri'], '/' . $route['route']) == 0
                    && (
                        strpos(strtolower($method), strtolower($value['method'])) !== false
                        || strtolower($value['method']) == 'any'
                    )
                ) {
                    $check = true;
                }
            }
        }

        return $check;
    }

    /**
     * Get permissions by user
     *
     * @param string $user_id
     *
     * @return array
     */
    public static function getRoutes(string $user_id)
    {
        if ($cache = Cache::get("menu")) {
            if (isset($cache['route-' . Auth::user()->id])) {
                return $cache['route-' . Auth::user()->id];
            }
        }
        
        $authAssignment = new AuthAssignment;

        $assigns = $authAssignment->getAuthAssignment($user_id);
        $assigns = array_column($assigns, 'item_name');
        if (count($assigns) == 0) {
            return [];
        }

        $routes = [];
        self::getFullRouteByParents($assigns, $routes);

        cache(['menu' => [
            'route-' . Auth::user()->id => $routes
        ]], 60 * 24);
        
        return $routes;
    }

    /**
     * (Auth) Get user menus by (user_id)
     *
     * @param string $user_id (required)
     *
     * @return array
     */
    private static function getMeusByUser(string $user_id)
    {
        $routes  = [];
        $menus   = [];
        $assigns = (new AuthAssignment)->getAuthAssignment($user_id);
        $assigns = array_column($assigns, 'item_name');

        self::getRouteByParents($assigns, $routes);

        $child_item = (new Menu)->getMenuByFiled($routes, 'route');
        self::getMenusByChild($child_item, $menus);
        
        return $menus;
    }

    /**
     * Get menus by childs
     *
     * @param array $child_item
     * @param int $pid
     */
    private static function getMenusByChild(array $child_item, array &$menu_item)
    {
        $menu_item = array_merge($child_item, $menu_item);
        $parents = array_filter(array_column($child_item, 'parent'));
        if (empty($parents)) {
            return false;
        } else {
            $menu_res = (new Menu)->getMenuByFiled($parents, 'id');
            self::getMenusByChild($menu_res, $menu_item);
        }
    }

    /**
     * Get routes by parents
     *
     * @param array $parents = [
     *      'parents1',
     *      'parents2',
     *      ...
     * ];
     * @param array &$routes = []
     * @param array $fileds = []
     *
     * @return array
     */
    private static function getFullRouteByParents(array $parents, array &$routes)
    {
        $parents = array_filter(array_map(function ($value) use (&$routes) {
            if (is_array($value)) {
                $child = $value['child'];
            } else {
                $child = $value;
            }

            if ($child[0] == '/') {
                $routes[] = $value;
            } else {
                return $child;
            }
        }, $parents));

        if (empty($parents)) {
            return false;
        } else {
            $item_arr = (new AuthItemChild)->getAuthItemChilds($parents);
            
            self::getFullRouteByParents($item_arr, $routes);
        }
    }

    /**
     * Get routes by parents
     *
     * @param array $parents = [
     *      'parents1',
     *      'parents2',
     *      ...
     * ];
     * @param array &$routes = []
     * @param array $fileds = []
     *
     * @return array
     */
    private static function getRouteByParents(array $parents, array &$routes)
    {
        $parents = array_filter(array_map(function ($value) use (&$routes) {
            if ($value[0] == '/') {
                $routes[] = $value;
            } else {
                return $value;
            }
        }, $parents));
        if (empty($parents)) {
            return false;
        } else {
            $item_arr = (new AuthItemChild)->getAuthItemChilds($parents);
            $item_arr = array_column($item_arr, 'child');
            
            self::getRouteByParents($item_arr, $routes);
        }
    }

    private static function recursion(array $list)
    {
        $items = [];
        foreach($list as $value){
            $items[$value['id']] = $value;
        }

        static $tree = [];
        foreach($items as $key => &$value){
            $value['data'] = json_decode($value['data'], true);
            if(isset($items[$value['parent']])){
                $items[$value['parent']]['children'][] = &$items[$key];
            }else{
                $tree[] = &$items[$key];
            }
        }
        unset($value);

        return $tree;
    }
}
