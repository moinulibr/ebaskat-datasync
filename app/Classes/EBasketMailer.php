<?php

namespace App\Classes;

use Illuminate\Support\Facades\Mail;
use App\Models\EmailTemplate;
use Illuminate\Support\Str;
use App\Models\Order;
use Illuminate\Support\Facades\Config;
use PDF;

class EBasketMailer
{
    public function __construct()
    {
        Config::set('mail.driver', env('MAIL_MAILER'));
        Config::set('mail.host', env('MAIL_HOST'));
        Config::set('mail.port', env('MAIL_PORT'));
        Config::set('mail.encryption', env('MAIL_ENCRYPTION'));
        Config::set('mail.username', env('MAIL_USERNAME'));
        Config::set('mail.password', env('MAIL_PASSWORD'));
    }

    public function sendAutoOrderMail(array $mailData,$id)
    {
        $title = "THANK YOU FOR YOUR PURCHASE";
        $temp = EmailTemplate::where('email_type','=',$mailData['type'])->first();
        $body = preg_replace("/{customer_name}/", $mailData['cname'] ,$temp->email_body);
        $body = preg_replace("/{order_amount}/", $mailData['oamount'] ,$body);
        $body = preg_replace("/{admin_name}/", $mailData['aname'] ,$body);
        $body = preg_replace("/{admin_email}/", $mailData['aemail'] ,$body);
        $body = preg_replace("/{order_number}/", $mailData['onumber'] ,$body);
        $body = preg_replace("/{website_title}/", $title ,$body);

        $data = [
            'email_body' => $body
        ];


        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = env('MAIL_FROM_ADDRESS');
        $objDemo->title = env('MAIL_FROM_NAME');
        $objDemo->subject = $temp->email_subject;

        try{
            Mail::send('admin.email.mailbody',$data, function ($message) use ($objDemo,$id) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
                $order = Order::findOrFail($id);
                $cart = unserialize($order->cart);
                $fileName = public_path('assets/temp_files/').Str::random(10).'.pdf';
                $pdf = PDF::loadView('print.order', compact('order', 'cart'))->save($fileName);
                $message->attach($fileName);
            });

        }
        catch (\Exception $e){
             //die($e->getMessage());
        }

        $files = glob('assets/temp_files/*'); //get all file names
        foreach($files as $file){
            if(is_file($file))
            unlink($file); //delete file
        }
    }

    public function sendAutoMail(array $mailData)
    {
        $title = "THANK YOU FOR YOUR PURCHASE";
        $temp = EmailTemplate::where('email_type','=',$mailData['type'])->first();
        $body = preg_replace("/{customer_name}/", $mailData['cname'] ,$temp->email_body);
        $body = preg_replace("/{order_amount}/", $mailData['oamount'] ,$body);
        $body = preg_replace("/{admin_name}/", $mailData['aname'] ,$body);
        $body = preg_replace("/{admin_email}/", $mailData['aemail'] ,$body);
        $body = preg_replace("/{order_number}/", $mailData['onumber'] ,$body);
        $body = preg_replace("/{website_title}/", $title ,$body);

        $data = [
            'email_body' => $body
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = env('MAIL_FROM_ADDRESS'); 
        $objDemo->title = env('MAIL_FROM_NAME');
        $objDemo->subject = $temp->email_subject;

        try{
            Mail::send('admin.email.mailbody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        }
        catch (\Exception $e){
            // die($e->getMessage());
        }
    }

    public function sendCustomMail(array $mailData)
    {

        $data = [
            'email_body' => $mailData['body']
        ];

        $objDemo = new \stdClass();
        $objDemo->to = $mailData['to'];
        $objDemo->from = env('MAIL_FROM_ADDRESS');
        $objDemo->title = env('MAIL_FROM_NAME');
        $objDemo->subject = $mailData['subject'];

        try{
            Mail::send('admin.email.mailbody',$data, function ($message) use ($objDemo) {
                $message->from($objDemo->from,$objDemo->title);
                $message->to($objDemo->to);
                $message->subject($objDemo->subject);
            });
        }
        catch (\Exception $e){
            return false;
        }
        return true;
    }

}