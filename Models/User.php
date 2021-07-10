<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    public function authTokens(): HasMany
    {
        return $this->hasMany(AuthToken::class, 'user_id');
    }
}
