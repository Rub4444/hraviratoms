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
        'card_class',
        'title_class',
        'desc_class',
        'link_class',
    ];

    protected $casts = [
        'config' => 'array',
    ];


    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public static function findActiveOrFail($id)
    {
        return self::where('is_active', true)
            ->findOrFail($id);
    }

}
