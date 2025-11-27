<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvitationTemplate extends Model
{
    protected $fillable = [
        'key',
        'name',
        'description',
        'preview_image',
        'vue_component',
        'is_active',
    ];

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }
}
