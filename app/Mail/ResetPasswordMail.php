<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;
    public $token, $resetUrl, $email;
    public function __construct($token,$resetUrl, $email)
    {
        $this->resetUrl = $resetUrl;
        $this->token =$token;
        $this->email = $email;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Password Reset Request',

        );
    }

    public function content(): Content
    {
//        $resetUrl=config('app.frontend_url').'/reset-password?token='.$this->token. '&email='.$this->email;
        return new Content(
             markdown:'emails.password-reset',
             with: [
                 'url' => $this->resetUrl,
                 'token' => $this->token,
                 'email' => $this->email,
        ],


//            view: 'view.name',

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
