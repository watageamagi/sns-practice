<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceAccess extends Model {
    protected $fillable = ['id', 'ip', 'description'];
    protected $hidden = ['updated_at', 'created_at'];
}
