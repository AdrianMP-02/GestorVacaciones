<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacationDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'total_days',
        'used_days',
        'pending_days',
        'adjustment_days',
        'notes'
    ];

    /**
     * Obtener el usuario al que pertenece este registro
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calcular el balance de días disponibles
     */
    public function getAvailableDaysAttribute()
    {
        return $this->total_days + $this->adjustment_days - $this->used_days - $this->pending_days;
    }

    /**
     * Verificar si hay suficientes días disponibles
     */
    public function hasSufficientDays($requestedDays)
    {
        return $this->getAvailableDaysAttribute() >= $requestedDays;
    }
}
