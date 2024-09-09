<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\SiteInfo;
use App\Models\EmailTemplate;
use App\Models\OrderModel;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\AddresModel;

class OrdersEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $orderdata;
    public $orderItem;
    public $user;
    public $siteinfo;
    public $emailContent;
    public $billing_address;
    public $shipping_address ;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderdata)
    {

        $this->orderdata = $orderdata;
        $this->orderItem = OrderItem::where('order_id',$orderdata['order_id'])->get();
        $this->user = User::find($orderdata['user_id']);
        $this->siteinfo = SiteInfo::first();
        $this->siteinfo['logo'] = env('AWS_URL').'public/storage/'.$this->siteinfo['logo'];


        $address = explode(',', $orderdata->address);
        $addressCount = count($address);
        if ($addressCount == 1) {
            $this->shipping_address = AddresModel::find($orderdata->address);
            $this->billing_address = $this->shipping_address;
        } else {
            $address1 = AddresModel::find($address[0]);
            if ($address1->address_type == 'shipping_address') {
                $this->shipping_address = $address1;
            } else {
                $this->billing_address = $address1;
            }

            $address2 = AddresModel::find($address[1]);
            if ($address2->address_type == 'billing_address') {
                $this->billing_address = $address2;
            } else {
                $this->shipping_address = $address2;
            }
        }

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user->email = 'testing.swapit@gmail.com';
        $this->user->first_name = 'Swap';
        $this->user->last_name = 'User';
        if($this->orderdata->status =='FAILED')
        {
            $subject = 'SAMA : Your order has been failed - ';
            $this->emailContent = EmailTemplate::where('group','send_failed_order_email_to_customer')->first();
        }else{
             $subject = 'SAMA : Your order has been received!';
             $this->emailContent = EmailTemplate::where('group','send_success_order_email_to_customer')->first();
        }
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->to($this->user->email)
                    ->subject($subject)
                    ->view('emails.orders')
                    ->with([
                        'username' => $this->user->first_name .' '.$this->user->last_name,
                        'siteinfo' => $this->siteinfo,
                        'emailContent'=>$this->emailContent,
                        'ordervalue'=>$this->orderdata,
                        'orderItem'=>$this->orderItem,
                        'shipping_address'=>$this->shipping_address,
                        'billing_address'=>$this->billing_address
                    ]);
    }
}
