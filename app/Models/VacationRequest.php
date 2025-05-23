<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VacationRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'type',
        'status',
        'comment',
        'approver_id',
        'approval_date'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approval_date' => 'datetime',
    ];

    /**
     * Obtener el usuario que hizo esta solicitud
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener el usuario que aprobó/rechazó esta solicitud
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Calcular total de días laborables
     */
    public function getWorkingDaysAttribute()
    {
        // Implementaremos esta lógica más adelante
        return 0;
    }

    /**
     * Verificar si la solicitud está pendiente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Verificar si la solicitud está aprobada
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Verificar si la solicitud está rechazada
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Verificar si la solicitud está cancelada
     */
    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    /**
     * Obtener las notificaciones relacionadas con esta solicitud
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
