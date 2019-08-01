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
     * (Auth) Get user menus by (user_id)
     *
     * @param string $user_id (required)
     *
     * @return array
     */
    public static function getMeusByUser(string $user_id)
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
    public static function getMenusByChild(array $child_item, array &$menu_item)
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
    public static function getRouteByParents(array $parents, array &$routes)
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
