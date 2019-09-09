<?php
namespace App;
use Illuminate\Database\Eloquent\Model;


class Field extends Model 
{
    // protected $table = 'fields';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'crop_type', 'area', 'slug',
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

    public function user()
    {
        return $this->belongsToMany('App\User', 'field_user', 'field_id', 'user_id');

    }
}