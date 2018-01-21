<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class EntryLocation
 * @package App
 */
class EntryLocation extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
         'latitude', 'longitude', 'entry_id', 'location_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

}
