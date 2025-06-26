<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eras extends Model
{

    use HasFactory;
   protected $fillable = [
    'tamX',
    'tamY',
    'tamX',
    'posX',
    'posY',
    'estado',
    'fk_Lotes',
   ];
}
