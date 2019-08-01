<?php

namespace Gurudin\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    public $timestamps = false;

    protected $attributes = [
        'title'  => '',
        'parent' => null,
        'route'  => null,
        'order'  => 0,
        'data'   => null
    ];
    protected $fillable = [
        'title',
        'parent',
        'route',
        'order',
        'data'
    ];

    /**
     * Get menu (by id)
     *
     * @return array
     */
    public function getMenu($id = 0)
    {
        if (!empty($id)) {
            $result = $this->where(['id' => $id])->first();
            $result = $result->toArray();
            if (!empty($result['parent'])) {
                $parent_menu = $this->where(['id' => $result['parent']])->select('title')->first();
                $result['parent_name'] = $parent_menu['title'];
            }
        } else {
            $result = [];
            $this->orderBy('id', 'desc')->chunk(100, function ($items) use (&$result) {
                foreach ($items as $item) {
                    $result[] = $item->toArray();
                }
            });
            foreach ($result as &$menu) {
                foreach ($result as $value) {
                    if ($menu['parent'] == $value['id']) {
                        $menu['parent_name'] = $value['title'];
                    }
                }
            }
            unset($menu);
        }

        return $result;
    }

    /**
     * Get menus by fileds
     *
     * @param array $arr = [ (required)
     *      '/menu',
     *      '/route',
     *      ...
     * ];
     * @param string $key = (id or route ro parent)
     *
     * @return array
     */
    public function getMenuByFiled(array $arr, $key = 'id')
    {
        $result = [];
        $this->orderBy('order', 'desc')->whereIn($key, $arr)->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });
        
        return $result;
    }
}
