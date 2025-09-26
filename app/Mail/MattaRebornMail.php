<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use App\Models\TrxEmailLog;

// source = https://mailtrap.io/blog/send-email-in-laravel/

class MattaRebornMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(private $mailData)
    {
        // dd($mailData);
        $trx = new TrxEmailLog;
        $trx->title = $mailData['title'];
        $trx->filename = $mailData['filename'];
        $trx->data = json_encode($mailData['data']);
        $trx->to = json_encode($mailData['to']);
        $trx->save();
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            // from: new Address('example@example.com', 'Test Sender'),
            subject: $this->mailData['title'],
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
            // markdown: $this->mailData['filename'],
            view: $this->mailData['filename'],
            with: ['data' => $this->mailData['data']],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        if(!empty($this->mailData['attachments'])){
            return [
                Attachment::fromPath($this->mailData['attachments'])->as($this->mailData['namafile'])->withMime('application/pdf'),
            ];
        }else{
            return [];
        }
    }
}
