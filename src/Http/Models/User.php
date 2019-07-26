<?php
namespace Gurudin\LaravelAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get user item.
     *
     * @param string $id
     *
     * @return array
     */
    public function getUser(string $id = '')
    {
        if ($id == '') {
            $result = [];
            $this->select(['id', 'name', 'email', 'created_at'])
                ->orderBy('id', 'asc')
                ->chunk(100, function ($items) use (&$result) {
                    foreach ($items as $item) {
                        $result[] = $item->toArray();
                    }
                });
        } else {
            $result = $this->select(['id', 'name', 'email', 'created_at'])->where(['id' => $id])->first();
        }
        
        return $result;
    }

    /**
     * Remove user.
     *
     * @param Gurudin\LaravelAdmin\Models\AuthAssignment $authAssignment
     * @param string $id
     *
     * @return bool
     */
    public function removeUser(AuthAssignment $authAssignment, string $id)
    {
        DB::beginTransaction();
        if (!$authAssignment->removeAuthAssignmentsById($id)) {
            DB::rollBack();
            return false;
        }

        if (!$this->where(['id' => $id])->delete()) {
            DB::rollBack();
            return false;
        }
        DB::commit();

        return true;
    }
}
