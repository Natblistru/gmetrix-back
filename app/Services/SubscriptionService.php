<?php

namespace App\Services;

use App\Models\Subscriber;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SubscriptionNotification;

class SubscriptionService
{
    public function subscribe($email)
    {

        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
          // Log::info('SubscriptionService dupa validare', ['errors' => $validator->errors()->all(), 'email' => $email]);
          return response()->json([
              'status' => 422,
              'errors' => $validator->messages(),
          ]);
        }

        Subscriber::updateOrCreate(
          ['email' => $email],
        );

        $subscriber = Subscriber::where('email', $email)->first();
              
        Notification::send($subscriber, new SubscriptionNotification());
    }

    public function notifyAllSubscribers($letterText)
    {
        $allSubscribers = Subscriber::where('subscribed',1)->get();

        Notification::send($allSubscribers, new SubscriptionNotification($letterText));
    }

    public function unsubscribe($email)
    {
        $user = Subscriber::where('email', $email)->first();

        if ($user) {
            $user->update(['subscribed' => false]);
        }

        return view('unsubscribe.success');
    }
}
