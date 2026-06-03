<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;
use App\Models\Student;
use App\Models\Teacher;
use App\Mail\ForgotPasswordMail;
use Mail;
use Str;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'first_name' => 'required|string|max:191',
            'last_name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'role' => 'required|string|in:student,teacher',
            'password' => 'required|min:8',
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }
        else
        {
            $allowedEmails = [
                'bernavschin@gmail.com',
                'andreicravcenco346@gmail.com',  // 01.06.2026 - 02.09.2026
                'ciugureanualexandru16@gmail.com', // 02.06.2026 - 02.09.2026
                'isacarrur270@gmail.com',          // 03.06.2026 - 03.09.2026
                'teodorhincu@gmail.com',           // 03.06.2026 - 03.09.2026
                'tagadiucdaniil@gmail.com',        // 03.06.2026 - 03.09.2026
                'captain.danleo@gmail.com',        // 03.06.2026 - 03.09.2026
                'turcuvalerika7@gmail.com',        // 03.06.2026 - 03.04.2027

                'student1@gmail.com',
                'teacher1@gmail.com',
            ];

            $email = strtolower(trim($request->email));

            if (!in_array($email, $allowedEmails, true)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.',
                ], 403);
            }

            $user = User::create([
                'name' => "{$request->first_name} {$request->last_name}",
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);

            if ($request->role === 'student') {
                Student::create([
                    'name' => "{$request->first_name} {$request->last_name}",
                    'user_id' => $user->id,
                    'status' => 0,
                ]);
            } elseif ($request->role === 'teacher') {
                Teacher::create([
                    'name' => "{$request->first_name} {$request->last_name}",
                    'user_id' => $user->id,
                    'status' => 0,
                ]);
            }

            $token = $user->createToken($user->email.'_Token')->plainTextToken;

            return response()->json([
                'status'=>201,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Registered Successfully',
            ]);
        }
    }



    public function login(Request $request) {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required',
        ]);
        
        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }
        else
        {

            $allowedEmails = [
                'bernavschin@gmail.com',
                'andreicravcenco346@gmail.com',  // 01.06.2026 - 02.09.2026
                'ciugureanualexandru16@gmail.com', // 02.06.2026 - 02.09.2026
                'isacarrur270@gmail.com',          // 03.06.2026 - 03.09.2026
                'teodorhincu@gmail.com',           // 03.06.2026 - 03.09.2026
                'tagadiucdaniil@gmail.com',        // 03.06.2026 - 03.09.2026
                'captain.danleo@gmail.com',        // 03.06.2026 - 03.09.2026
                'turcuvalerika7@gmail.com',        // 03.06.2026 - 03.04.2027

                'student1@gmail.com',
                'teacher1@gmail.com',
            ];

            $email = strtolower(trim($request->email));

            if (!in_array($email, $allowedEmails, true)) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied.',
                ], 403);
            }
            $user = User::where('email', $request->email)->first();
 
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'=>401,
                    'message'=>'Invalid Credentials',
                ]);
            }
            else
            {
                if($user->role_as == 1) //1=Admin
                {
                    $role = 'admin';
                    $token = $user->createToken($user->email.'_AdminToken', ['server:admin'])->plainTextToken;
                }
                else
                {
                    $role = '';
                    $token = $user->createToken($user->email.'_Token', [''])->plainTextToken;
                }

                $roleId = null;

                $teacher = Teacher::where('user_id', $user->id)->first();
                if ($teacher) {
                    $roleId = $teacher->id;
                    $role = 'teacher';
                }
    
                if (!$roleId) {
                    $student = Student::where('user_id', $user->id)->first();
                    if ($student) {
                        $roleId = $student->id;
                        $role = 'student';
                    }
                }

                return response()->json([
                    'status'=>200,
                    'username'=>$user->name,
                    'roleId'=>$roleId,
                    'token'=>$token,
                    'message'=>'Login Successfully',
                    'role'=>$role,
                ]);
            }
        }
    }

    public function logout()
    {
        // auth()->user()->tokens()->delete();
        return response()->json([
            'status'=>200,
            'message'=>'Logged Out Successfully',
        ]);
    }

    public function forgot(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }
        else
        {
            $user = User::where('email', $request->input('email'))->first();

            if(!$user || !$user->email) {
                return response()->json([
                    'status'=>404,
                    'message'=>'Incorect Email Address Provided',
                ]);
            }

            $user->remember_token = Str::random(30);
            $user->save();

            Mail::to($user->email)->send(new ForgotPasswordMail($user));

            return response()->json([
                'status'=>200,
                'message'=>'A code has been sent to your email address.',
            ]);
        }
    }

    public function reset($token, Request $request): JsonResponse
    {
        $validator =  Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);
        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->messages(),
            ]);
        }
        else
        {
        
            $user = User::where('remember_token',$token)->first();

            if(!$user ) {
                return response()->json(['message' => 'The token is not valid'], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            $user->tokens()->delete();

            return response()->json([
                'status'=>200,
                // 'username'=>$user->name,
                // 'token'=>$token,
                'message'=>'Password reset successfully',
                // 'role'=>$role,
            ]);
        }

    }
}
