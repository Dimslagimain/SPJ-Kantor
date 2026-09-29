<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevisionHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'revision_date' => 'datetime',
            'resolved_date' => 'datetime',
        ];
    }

    public function spj(): BelongsTo
    {
        return $this->belongsTo(Spj::class);
    }
}
