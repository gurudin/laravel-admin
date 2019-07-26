<?php
namespace Gurudin\LaravelAdmin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuthAssignment extends Model
{
    protected $table = 'auth_assignment';
    public $timestamps = false;

    /**
     * Get auth assignment (by user_id)
     *
     * @param string $user_id (required)
     *
     * @return array
     */
    public function getAuthAssignment(string $user_id)
    {
        $result = [];
        $this->where([
            'user_id' => $user_id,
        ])->orderBy('item_name', 'asc')->chunk(100, function ($items) use (&$result) {
            foreach ($items as $item) {
                $result[] = $item->toArray();
            }
        });
        
        return $result;
    }

    /**
     * Create auth assignment
     *
     * @param array $data = [
     *      [
     *          "item_name" => "User role",
     *          "user_id" => "1"
     *      ],
     *      ...
     * ];
     *
     * @return bool
     */
    public function createAuthAssignments(array $data)
    {
        $insetData = [];
        foreach ($data as $item) {
            $insetData[] = [
                'user_id'   => $item['user_id'],
                'item_name' => $item['item_name']
            ];
        };

        return $this->insert($insetData);
    }

    /**
     * Remove auth assignment
     *
     * @param array $data = [
     *      [
     *          "item_name" => "User role",
     *          "user_id" => "1"
     *      ],
     *      ...
     * ];
     *
     * @return bool
     */
    public function removeAuthAssignments(array $data)
    {
        DB::beginTransaction();
        foreach ($data as $item) {
            $res = $this->where([
                'user_id'   => $item['user_id'],
                'item_name' => $item['item_name']
            ])->delete();
            
            if (!$res) {
                DB::rollBack();
                return false;
            }
        }
        DB::commit();
        
        return true;
    }
}
