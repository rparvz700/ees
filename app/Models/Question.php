<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'text',
        'dimension',
        'reverse_coded',
    ];

    protected $casts = [
        'dimension' => 'array'
    ];

    /**
     * A question can have many responses.
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
