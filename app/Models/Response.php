<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_order',
        'department',
        'group',
        'submitted',
        'submitted_at',
        'flags',
        'question_id',
        'answer',
    ];

    protected $casts = [
        'question_order' => 'array',
        'flags' => 'array',
        'submitted' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    /**
     * A response belongs to a specific question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
