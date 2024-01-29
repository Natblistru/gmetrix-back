<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\SubscriptionService;

class SubscriberController extends Controller
{
    protected $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function subscribe(Request $request)
    {
        $email = $request->input('email');
        $this->subscriptionService->subscribe($email);

        Log::info('SubscriberController', ['email' => $email]);

        return response()->json(['message' => 'Successfully subscribed!']);
    }

    public function notifyAllSubscribers(Request $request)
    {
        $letterText = $request->input('letterText');

        $this->subscriptionService->notifyAllSubscribers($letterText);

        return response()->json(['message' => 'Notificări trimise cu succes']);
    }



}
