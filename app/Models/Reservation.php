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

    protected $hidden = [
        'confirmation_code'
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

    public static function generateConfirmationCode(): string {
        return (string) rand(1000, 9999);
    }

    public static function hasOverlappingReservations(int $tableId, string $start, string $end): bool
    {
        $query = Reservation::query()
            ->where('table_id', "=", $tableId)
            ->where("start", "<=", $end)
            ->where("end", ">=", $start);
        return $query->exists();
    }
}
