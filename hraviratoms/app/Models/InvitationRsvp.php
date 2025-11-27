<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitationRsvp extends Model
{
    protected $fillable = [
        'invitation_id',
        'guest_name',
        'guest_phone',
        'status',
        'guests_count',
        'message',
        'guest_ip',
    ];

    public function invitation()
    {
        return $this->belongsTo(Invitation::class);
    }
}
