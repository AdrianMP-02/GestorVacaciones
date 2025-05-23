<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'date',
        'is_recurring'
    ];

    protected $casts = [
        'date' => 'date',
        'is_recurring' => 'boolean',
    ];

    /**
     * Scope para filtrar por año
     */
    public function scopeForYear($query, $year)
    {
        return $query->whereYear('date', $year)
            ->orWhere('is_recurring', true);
    }

    /**
     * Verificar si una fecha es festiva
     */
    public static function isHoliday($date)
    {
        $dateObj = is_string($date) ? new \DateTime($date) : $date;

        return self::whereDate('date', $dateObj->format('Y-m-d'))
            ->orWhere(function ($query) use ($dateObj) {
                $query->where('is_recurring', true)
                    ->whereMonth('date', $dateObj->format('m'))
                    ->whereDay('date', $dateObj->format('d'));
            })
            ->exists();
    }

    // Si los días festivos pueden ser específicos por departamento
    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }
}
