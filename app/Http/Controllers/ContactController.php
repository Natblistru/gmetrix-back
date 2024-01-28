<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendEmail(Request $request)
    {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $phone = $request->input('phone');
        $messageBody = $request->input('message');

        $name = $firstName . ' ' . $lastName;
        $toEmail = 'nataberna29@gmail.com'; 
        $subject = 'Trimitere formular de contact - Platforma educationala';

        $data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'messageBody' => $messageBody,
        ];

        try {
            Mail::send('emails.contact', $data, function ($message) use ($toEmail, $subject) {
                $message->to($toEmail)->subject($subject);
            });

            return response()->json(['code' => 200, 'status' => 'Message Sent']);
        } catch (\Exception $e) {
            return response()->json(['code' => 500, 'status' => 'Error sending message', 'error' => $e->getMessage()]);
        }
    }
}
