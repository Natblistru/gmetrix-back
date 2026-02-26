<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Log;


class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')
            ->stateless()
            ->user();

        $email = strtolower(trim($googleUser->getEmail()));

        $nameRaw = trim((string) ($googleUser->getName()
            ?? $googleUser->getNickname()
            ?? ''));

        $nameRaw = preg_replace('/\s+/', ' ', $nameRaw); // normalizează spațiile

        $firstName = $nameRaw;
        $lastName = null;

        if ($nameRaw !== '' && str_contains($nameRaw, ' ')) {
            $parts = explode(' ', $nameRaw, 2); // 2 = primul cuvânt + restul
            $firstName = $parts[0] ?: $nameRaw;
            $lastName = $parts[1] ?: null;
        }

        if ($nameRaw === '') {
            $nameRaw = 'User';
            $firstName = 'User';
            $lastName = null;
        }

        // 1) găsești sau creezi user-ul
        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $nameRaw,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => null,
            ]);
        } else {
            // opțional: actualizezi numele dacă vrei
            if (! $user->name) {
                $updates = [];

                if (empty($user->name)) {
                    $updates['name'] = $nameRaw;
                }
                if (empty($user->first_name)) {
                    $updates['first_name'] = $firstName;
                }
                if (empty($user->last_name) && $lastName) {
                    $updates['last_name'] = $lastName;
                }

                if (!empty($updates)) {
                    $user->update($updates);
                }
            }
        }

        // 2) determinăm rolul și roleId (cu regulile tale)
        $role = '';
        $roleId = null;

        $teacher = Teacher::where('user_id', $user->id)->first();
        $student = Student::where('user_id', $user->id)->first();

        if ((int) $user->role_as === 1) {
            $role = 'admin';
            // admin nu are roleId (sau poți păstra null)
        } elseif ($teacher) {
            $role = 'teacher';
            $roleId = $teacher->id;
        } elseif ($student) {
            $role = 'student';
            $roleId = $student->id;
        } else {
            // implicit student + creezi student nou
            $student = Student::create([
                'name' => $user->name,
                'user_id' => $user->id,
                'status' => 0,
            ]);

            $role = 'student';
            $roleId = $student->id;
        }

        // 3) token: AdminToken dacă admin, altfel Token normal
        if ($role === 'admin') {
            $token = $user->createToken(
                $user->email . '_AdminToken',
                ['server:admin']
            )->plainTextToken;
        } else {
            $token = $user->createToken(
                $user->email . '_Token',
                ['']
            )->plainTextToken;
        }

        // 4) redirect către frontend
        $frontUrl = config('app.frontend_url', 'http://localhost:3000');

        $qs = http_build_query([
            'token' => $token,
            'username' => $user->name,
            'role' => $role,
            'roleId' => $roleId ?? 0, // ca să apară mereu în URL
        ]);

        return redirect()->to($frontUrl . '/auth/google/callback?' . $qs);
    }

}
