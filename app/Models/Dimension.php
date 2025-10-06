<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Dimension extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'slug',
        'description',
        'weight', // optional: if you want weighted dimensions later
    ];

}