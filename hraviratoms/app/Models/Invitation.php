<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\InvitationStatus;

class Invitation extends Model
{
    use SoftDeletes;

    const STATUS_PENDING   = 'pending';
    const STATUS_PUBLISHED = 'published';
    const STATUS_REJECTED  = 'rejected';

    protected $fillable = [
        'invitation_template_id',
        'user_id',
        'slug',
        'title',
        'bride_name',
        'groom_name',
        'date',
        'time',
        'venue_name',
        'venue_address',
        'dress_code',
        'data',
        'is_published',
        'meta_title',
        'meta_description',
        'status',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_comment',
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date',
        'is_published' => 'boolean',
        'status' => InvitationStatus::class,
    ];

    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvitationTemplate::class, 'invitation_template_id');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rsvps()
    {
        return $this->hasMany(InvitationRsvp::class);
    }

}
