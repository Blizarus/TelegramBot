<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'telegram_id', 'name', 'email', 'phone'
    ];

    // Аптечка пользователя
    public function medicines()
    {
        return $this->hasMany(UserMedicine::class);
    }

    // Кастомные лекарства, созданные пользователем
    public function customMedicines()
    {
        return $this->hasMany(CustomMedicine::class);
    }

    // Семьи, в которых состоит пользователь
    public function families()
    {
        return $this->belongsToMany(Family::class, 'family_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // Семья, которую создал пользователь
    public function createdFamily()
    {
        return $this->hasOne(Family::class, 'created_by');
    }
}
