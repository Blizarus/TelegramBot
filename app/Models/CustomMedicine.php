<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomMedicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_name', 'inn', 'dosage_form', 'dosage', 'pack_size',
        'manufacturer', 'country', 'description', 'image_url'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userMedicines()
    {
        return $this->hasMany(UserMedicine::class);
    }
}
