<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Verificar_Correo extends Mailable
{
    use Queueable, SerializesModels;
    protected $user,$url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $url)
    {
        $this->user=$user;
        $this->url=$url;
    }


    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Send Mail',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'primero',
            with: [
                'name' => $this->user->name,
                'email' => $this->user->email,
                'id'=> $this->user->id,
                'url' => $this->url,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
