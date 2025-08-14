<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'specialty', 'contact_info'];

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
