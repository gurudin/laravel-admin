<?php
namespace Gurudin\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;

class AuthItem extends Model
{
    protected $table = 'auth_item';
    public $timestamps = false;

    const TYPE_ROLE = 1;
    const TYPE_PERMISSION = 2;

    /**
     * Get permissions
     *
     * @return array
     */
    public function getPermission()
    {
        return $this->getItems(self::TYPE_PERMISSION)['permission'];
    }

    /**
     * Get Roles
     *
     * @return array
     */
    public function getRole()
    {
        return $this->getItems(self::TYPE_ROLE);
    }

    /**
     * Get Routes
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->getItems(self::TYPE_PERMISSION)['route'];
    }

    /**
     * Get Items
     *
     * @return array
     */
    public function getItems(int $type)
    {
        $result = [];
        $this->where(['type' => $type])->orderBy('name', 'desc')->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });
        if ($type == self::TYPE_PERMISSION) {
            $route      = [];
            $permission = [];
            foreach ($result as $value) {
                if ($value['name'][0] == '/') {
                    $route[] = $value;
                } else {
                    $permission[] = $value;
                }
            }
            $item = [
                'route' => $route,
                'permission' => $permission,
            ];
        } else {
            $item = $result;
        }
        
        return $item;
    }

    /**
     * Remove by (name & method)
     *
     * @param array $data
     * @param int $type
     *
     * @return bool
     */
    public function removeItem(array $data, int $type = self::TYPE_ROLE)
    {
        $count = $this->where(['name' => $data['name'], 'method' => ($data['method'] ? $data['method'] : '')])->count();
        if ($count == 0) {
            return true;
        }
        $m = new AuthItemChild;
        if ($type == self::TYPE_ROLE) {
            $m->removeItemChild(['parent' => $data['name'], 'method' => ($data['method'] ? $data['method'] : '')]);
        } else {
            $m->where(['child' => $data['name'], 'method' => ($data['method'] ? $data['method'] : '')])->delete();
        }
        
        return $this->where(['name' => $data['name'], 'method' => ($data['method'] ? $data['method'] : '')])->delete();
    }
}
