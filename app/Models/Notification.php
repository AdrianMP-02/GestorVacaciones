<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vacation_request_id',
        'type',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Obtener el usuario al que pertenece esta notificación
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtener la solicitud de vacaciones relacionada con esta notificación
     */
    public function vacationRequest()
    {
        return $this->belongsTo(VacationRequest::class);
    }

    /**
     * Marcar la notificación como leída
     */
    public function markAsRead()
    {
        $this->is_read = true;
        $this->save();

        return $this;
    }

    /**
     * Scope para notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
