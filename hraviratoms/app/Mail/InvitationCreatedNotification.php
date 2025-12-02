<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationCreatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Invitation $invitation;
    public bool $isPublicRequest;

    /**
     * @param Invitation $invitation
     * @param bool $isPublicRequest true если создано через публичную форму
     */
    public function __construct(Invitation $invitation, bool $isPublicRequest = false)
    {
        $this->invitation = $invitation;
        $this->isPublicRequest = $isPublicRequest;
    }

    public function build()
    {
        $subject = $this->isPublicRequest
            ? 'LoveLeaf • Новая заявка на приглашение'
            : 'LoveLeaf • Новое приглашение создано в админке';

        return $this
            ->subject($subject)
            ->markdown('emails.invitations.created');
    }
}
