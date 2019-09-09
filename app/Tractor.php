<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Report;

class Tractor extends Model 
{
    // protected $table = 'fields';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id',
    ];

    public function tractor()
    {
        return $this->hasMany('App\Report');

    }


    public function user()
    {
        return $this->belongsToMany('App\User', 'tractor_users', 'tractor_id', 'user_id');

    }
}