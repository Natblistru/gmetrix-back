<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ConsultationRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'videoLesson_id' => 'required|integer',
            'student_id' => 'required|integer',            
            'lesson_title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'scheduled_at' => 'required|date',
        ]);

        $consultation = ConsultationRequest::create([
            'videoLesson_id' => $validated['videoLesson_id'],
            'student_id' => $validated['student_id'],            
            'lesson_title' => $validated['lesson_title'],
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'suma' => 200,
            'scheduled_at' => $validated['scheduled_at'],
            'status' => 0,
        ]);

        Mail::send([], [], function ($message) use ($validated) {
            $message->to($validated['email'])
                ->subject('Confirmare programare consultație')
                ->html("
                    <h2>Consultația a fost programată</h2>

                    <p>Bună, {$validated['name']}!</p>

                    <p>Ai programat o consultație pentru lecția:</p>
                    <p><strong>{$validated['lesson_title']}</strong></p>

                    <p><strong>Data și ora:</strong> {$validated['scheduled_at']}</p>
                    <p><strong>Telefon:</strong> {$validated['phone']}</p>

                    <p>Vei fi contactat(ă) pentru confirmarea finală.</p>

                    <br>
                    <p>Cu respect,<br>echipa platformei</p>
                ");
        });

        Mail::send([], [], function ($message) use ($validated) {
            $message->to(config('mail.from.address'))
                ->subject('Cerere nouă de consultație')
                ->html("
                    <h2>Cerere nouă de consultație</h2>

                    <p><strong>Nume:</strong> {$validated['name']}</p>
                    <p><strong>Email:</strong> {$validated['email']}</p>
                    <p><strong>Telefon:</strong> {$validated['phone']}</p>
                    <p><strong>Lecția:</strong> {$validated['lesson_title']}</p>
                    <p><strong>Data și ora:</strong> {$validated['scheduled_at']}</p>
                ");
        });

        return response()->json([
            'status' => 200,
            'message' => 'Consultația a fost programată.',
            'data' => $consultation,
        ]);
    }

    public function cancel($id)
    {
        $consultation = ConsultationRequest::findOrFail($id);

        // status = 1 înseamnă anulată / ștearsă logic
        $consultation->update([
            'status' => 1,
        ]);

        Mail::send([], [], function ($message) use ($consultation) {
            $name = e($consultation->name);
            $lessonTitle = e($consultation->lesson_title);
            $scheduledAt = e($consultation->scheduled_at);

            $message->to($consultation->email)
                ->subject('Confirmare anulare consultație')
                ->html("
                    <h2>Consultația a fost anulată</h2>
                    <p>Bună, {$name}!</p>
                    <p>Consultația programată pentru lecția:</p>
                    <p><strong>{$lessonTitle}</strong></p>
                    <p><strong>Data și ora:</strong> {$scheduledAt}</p>
                    <p>a fost anulată cu succes.</p>
                    <br>
                    <p>Cu respect,<br>echipa platformei</p>
                ");
        });

        Mail::send([], [], function ($message) use ($consultation) {
            $name = e($consultation->name);
            $lessonTitle = e($consultation->lesson_title);
            $scheduledAt = e($consultation->scheduled_at);
            $email = e($consultation->email);
            $phone = e($consultation->phone);

            $message->to(config('mail.from.address'))
                ->subject('Consultație anulată')
                ->html("
                    <h2>Consultație anulată</h2>
                    <p><strong>Nume:</strong> {$name}</p>
                    <p><strong>Email:</strong> {$email}</p>
                    <p><strong>Telefon:</strong> {$phone}</p>
                    <p><strong>Lecția:</strong> {$lessonTitle}</p>
                    <p><strong>Data și ora:</strong> {$scheduledAt}</p>
                ");
        });

        return response()->json([
            'status' => 200,
            'message' => 'Consultația a fost anulată.',
            'data' => $consultation,
        ]);
    }
}

