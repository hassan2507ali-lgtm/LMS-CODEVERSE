<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AnnouncementMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $messageBody;

    // Data yang dikirim dari controller akan diterima di sini
    public function __construct($title, $messageBody)
    {
        $this->title = $title;
        $this->messageBody = $messageBody;
    }

    // Mengatur Subjek Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Resmi Code Verse 🚀',
        );
    }

    // Mengatur Tampilan (Blade) Email
    public function content(): Content
    {
        return new Content(
            view: 'emails.announcement',
        );
    }
}