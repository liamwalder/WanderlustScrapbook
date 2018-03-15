<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class VerifyUser
 * @package App
 */
class VerifyUser extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
