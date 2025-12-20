<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'trade_name', 'inn', 'dosage_form', 'dosage', 'pack_size',
        'manufacturer', 'country', 'description', 'atc_code', 'image_url'
    ];

    public function userMedicines()
    {
        return $this->hasMany(UserMedicine::class);
    }
}
