<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'family_user')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Все лекарства в аптечках членов семьи (удобно для общего доступа)
    public function userMedicines()
    {
        return $this->hasManyThrough(UserMedicine::class, User::class);
    }
}
