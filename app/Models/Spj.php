<?php

namespace App\Models;

use Database\Factories\SpjFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spj extends Model
{
    /** @use HasFactory<SpjFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
            'due_date' => 'date',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'submitted_amount' => 'decimal:2',
        ];
    }
}
