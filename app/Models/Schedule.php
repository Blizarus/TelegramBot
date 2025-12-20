<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_medicine_id', 'time', 'frequency', 'days',
        'start_date', 'end_date', 'is_active', 'last_notified'
    ];

    protected $casts = [
        'days' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'last_notified' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function userMedicine()
    {
        return $this->belongsTo(UserMedicine::class);
    }

    // Удобный доступ к пользователю и лекарству
    public function user()
    {
        return $this->userMedicine->user ?? null;
    }

    // Исправленный метод - больше не используем details как отношение
    public function getMedicineDetailsAttribute()
    {
        if ($this->userMedicine) {
            return $this->userMedicine->medicine ?? $this->userMedicine->customMedicine;
        }
        return null;
    }
}
