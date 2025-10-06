<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'employee_code',
        'hr_id',
        'department',
        'is_tech',
        'submitted',
        'submitted_at',
        'eei',
        'dimension_scores',
        'reverse_inconsistency',
        'is_identical'
    ];

    // Add computed fields to JSON / array automatically
    protected $appends = ['is_tech_label', 'status_label'];

    // Accessor for is_tech
    public function getIsTechLabelAttribute()
    {
        return $this->is_tech ? 'Tech' : 'Non-Tech';
    }

    // Accessor for status
    public function getStatusLabelAttribute()
    {
        if ($this->submitted == 0 && is_null($this->submitted_at)) {
            return 'New';
        } elseif ($this->submitted == 0 && !is_null($this->submitted_at)) {
            return 'Draft';
        } elseif ($this->submitted == 1 && !is_null($this->submitted_at)) {
            return 'Completed';
        }

        return 'Unknown'; // fallback
    }
    /**
     * A submission has many responses.
     */
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}
