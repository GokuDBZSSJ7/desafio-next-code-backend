<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'address',
        'contact_info',
        'diagnosis'
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
