<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'medicine_id', 'custom_medicine_id',
        'quantity', 'total_quantity', 'expiration_date',
        'purchase_price', 'dosage_per_intake', 'notes'
    ];

    protected $dates = ['expiration_date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Лекарство из общего справочника
    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }

    // Кастомное лекарство
    public function customMedicine()
    {
        return $this->belongsTo(CustomMedicine::class);
    }

    /**
     * Получить детали лекарства (без использования аксессора)
     */
    public function getDetails()
    {
        if ($this->medicine) {
            return $this->medicine;
        }
        if ($this->customMedicine) {
            return $this->customMedicine;
        }
        return null;
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
