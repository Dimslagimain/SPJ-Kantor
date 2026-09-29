<?php

namespace App\Models;

use Database\Factories\SpjFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvalHistories(): HasMany
    {
        return $this->hasMany(ApprovalHistory::class);
    }

    public function revisionHistories(): HasMany
    {
        return $this->hasMany(RevisionHistory::class);
    }

    public function isAwaitingReview(): bool
    {
        return in_array($this->status, ['visitor1_review', 'visitor2_review', 'kepala_review', 'bendahara_review'], true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'submitted', 'visitor1_review' => 'Menunggu Persetujuan Visitor 1',
            'visitor2_review' => 'Menunggu Persetujuan Visitor 2',
            'kepala_review' => 'Menunggu Persetujuan Kepala Dinas',
            'bendahara_review' => 'Diproses Bendahara',
            'revision_visitor1' => 'Revisi Visitor 1',
            'revision_visitor2' => 'Revisi Visitor 2',
            'revision_kepala_dinas' => 'Revisi Kepala Dinas',
            'approved' => 'Disetujui Kepala Dinas',
            'completed' => 'Selesai',
            default => str_replace('_', ' ', ucfirst($this->status)),
        };
    }

    public function statusClass(): string
    {
        return str_starts_with($this->status, 'revision_')
            ? 'revision'
            : ($this->status === 'completed' || $this->status === 'approved' ? 'ready' : 'review');
    }
}
