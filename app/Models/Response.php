<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Response extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_order',
        'flags',
        'submission_id',
        'question_id',
        'answer',
    ];

    protected $casts = [
        'question_order' => 'array',
        'flags' => 'array',
    ];

    /**
     * A response belongs to a submission.
     */
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    /**
     * A response belongs to a question.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
