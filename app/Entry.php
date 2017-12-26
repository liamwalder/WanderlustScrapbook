<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Entry
 * @package App
 */
class Entry extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'location_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entryLocations()
    {
        return $this->hasMany(EntryLocation::class);
    }

}
