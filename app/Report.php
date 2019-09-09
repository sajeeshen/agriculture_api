<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Tractor;

class Report extends Model
{

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id','pivot', 'user_id', 'tractor_id', 'field_id', 'approved_user_id'
    ];
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'report_slug', 'report_date',  'approved_user_id'
    ];
    protected $casts = [
        'approved' => 'boolean',
    ];
    protected $dates = ['report_date'];

    public function tractor()
    {
        return $this->belongsTo(Tractor::class, 'tractor_id');

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }

    public function approved_user()
    {
        return $this->belongsTo(User::class, 'user_id');

    }
    
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');

    }
    
}