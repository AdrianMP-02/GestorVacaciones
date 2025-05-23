<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'department_id',
        'supervisor_id',
    ];

    /**
     * Los atributos que deben ocultarse.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben convertirse.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Obtener el rol del usuario
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtener el departamento del usuario
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Obtener el supervisor del usuario
     */
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * Obtener los subordinados del usuario
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'supervisor_id');
    }

    /**
     * Obtener las solicitudes de vacaciones del usuario
     */
    public function vacationRequests()
    {
        return $this->hasMany(VacationRequest::class);
    }

    /**
     * Obtener las solicitudes que este usuario debe aprobar
     */
    public function requestsToApprove()
    {
        return $this->hasMany(VacationRequest::class, 'approver_id');
    }

    /**
     * Obtener los días de vacaciones del usuario
     */
    public function vacationDays()
    {
        return $this->hasMany(VacationDay::class);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    /**
     * Verificar si el usuario es gerente
     */
    public function isManager()
    {
        return $this->role->name === 'manager';
    }

    /**
     * Verificar si el usuario es empleado
     */
    public function isEmployee()
    {
        return $this->role->name === 'employee';
    }
}
