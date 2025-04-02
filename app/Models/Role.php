<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Role extends Model
{
    protected $table = "roles";

    public function users(): Relation
    {
        return $this->hasMany(User::class);
    }
}
