<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'table_id',
        'guests',
        'confirmation_code',
        'confirmed',
        'start',
        'end',
    ];

    protected $table = "reservations";

    public function user(): Relation
    {
        return $this->belongsTo(User::class);
    }

    public function table(): Relation
    {
        return $this->belongsTo(Table::class);
    }
}
