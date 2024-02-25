<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SendEmail extends Mailable
{
    protected array $content;
    
    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function build()
    {
        $this->subject($this->content['subject']);
        $this->html('
            <div style="background-color: #f4f4f4; padding: 20px;">
                '.$this->content['body'].'
            </div>
        ');
        return $this;
    }
}
