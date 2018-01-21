<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class File
 * @package App
 */
class File extends Model
{
    use SoftDeletes;
    
    /**
     * @var array
     */
    protected $fillable = ['thumbnail', 'location_id', 'entry_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class);
    }

}
