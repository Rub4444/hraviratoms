<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invitation extends Model
{
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
    ];

    protected $casts = [
        'data' => 'array',
        'date' => 'date',
        'is_published' => 'boolean',
    ];

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
