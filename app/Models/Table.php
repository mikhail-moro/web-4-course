<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class Table extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'number',
        'seats',
    ];

    protected $table = "tables";

    public function reservations(): Relation
    {
        return $this->hasMany(Reservation::class);
    }

    public static function hasNotEnoughSeats($tableId, int $guests): bool
    {
        $table = Table::query()->findOrFail($tableId);
        return $table["seats"] < $guests;
    }
}
