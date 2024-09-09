<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteInfo;
use App\Models\EmailTemplate;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $siteinfo;
    public $emailContent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->siteinfo = SiteInfo::first();
        $this->siteinfo['logo'] = env('AWS_URL').'public/storage/'.$this->siteinfo['logo'];
        $this->emailContent = EmailTemplate::where('group','send_registratino_email')->first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->to($this->user->email)
                    ->subject('Welcome to Sama')
                    ->view('emails.user_registered')
                    ->with([
                        'firstName' => $this->user->first_name,
                        'lastName' => $this->user->last_name,
                        'siteinfo' => $this->siteinfo,
                        'emailContent'=>$this->emailContent,
                    ]);
    }
}
