<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteInfo;
use App\Models\EmailTemplate;

class UserVerification extends Mailable
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
        $this->emailContent = EmailTemplate::where('group','send_account_verification_email')->first();
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
                    ->subject('Your SAMA Account is Verified!')
                    ->view('emails.user_verification')
                    ->with([
                        'username' => $this->user->first_name .' '.$this->user->last_name ,
                        'siteinfo' => $this->siteinfo,
                        'emailContent'=>$this->emailContent,
                    ]);
    }
}
