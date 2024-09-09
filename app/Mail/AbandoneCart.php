<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteInfo;
use App\Models\EmailTemplate;
use App\Models\Cart;
use App\Models\User;

class AbandoneCart extends Mailable
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
    public function __construct($userdata)
    {
        $this->user = $userdata;
        $this->siteinfo = SiteInfo::first();
        $this->siteinfo['logo'] = env('AWS_URL').'public/storage/'.$this->siteinfo['logo'];
        $this->emailContent = EmailTemplate::where('group','send_abandoned_cart_email_to_customer')->first();
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
                    ->subject('SAMA : Your Items Are Almost Gone—Complete Your Purchase Now!')
                    ->view('emails.abandoned_cart')
                    ->with([
                        'username' => $this->user->first_name .' '.$this->user->last_name,
                        'siteinfo' => $this->siteinfo,
                        'emailContent'=>$this->emailContent,
                        'ordervalue'=>$this->orderdata,
                        'orderItem'=>$this->orderItem,
                    ]);
    }
}
