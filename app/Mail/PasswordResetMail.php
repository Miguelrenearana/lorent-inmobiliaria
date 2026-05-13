<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $url;
    public ?string $name;

    public function __construct(string $url, ?string $name = null)
    {
        $this->url = $url;
        $this->name = $name;
    }

    public function build()
    {
        return $this->subject('Recuperación de contraseña | Lorent Inmobiliaria')
                    ->view('emails.password_reset');
    }
}
