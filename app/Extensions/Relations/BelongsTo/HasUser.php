<?php

namespace App\Extensions\Relations\BelongsTo;

use App\Models\User;

trait HasUser
{
    public function user() {
        return $this->belongsTo(User::class);
    }
}
