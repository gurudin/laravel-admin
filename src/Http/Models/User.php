<?php
namespace Gurudin\LaravelAdmin\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
}
