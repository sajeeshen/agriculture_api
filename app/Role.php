<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class Role extends Model
{

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id','pivot','description'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'user_role', 'role_id', 'user_id');

    }

    
    
}