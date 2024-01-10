<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public static function index() {
        return User::all();
    }

    public static function show($id) {
        return User::find($id); 
    }

    public function findUserByName($name) {
        $user = User::with('teacher', 'student')->where('name', $name)->first();

        if ($user) {
            if ($user->student) {
                return response()->json(['user' => $user, 'role' => 'student'], 200);
            } elseif ($user->teacher) {
                return response()->json(['user' => $user, 'role' => 'teacher'], 200);
            } else {
                return response()->json(['user' => $user, 'role' => 'user'], 200);
            }
        } else {
            return response()->json(['message' => 'Utilizatorul nu a fost găsit'], 404);
        }
    }

    public static function allUsers(Request $request) {
        $search = $request->query('search');
        $sortColumn = $request->query('sortColumn');
        $sortOrder = $request->query('sortOrder');
        $page = $request->query('page', 1);
        $perPage = $request->query('perPage', 10);
    
        $allowedColumns = ['id', 'first_name', 'last_name', 'email'];
    
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'id';
        }
    
        $columnTableMapping = [
            'id' => 'U',
            'first_name' => 'U',
            'last_name' => 'U',
            'email' => 'U',
        ];
    
        $sqlTemplate = "
        SELECT U.id,
            U.first_name,
            U.last_name,
            U.email,
            CASE
                WHEN T.status IS NOT NULL THEN 'TEACHER'
                WHEN S.status IS NOT NULL THEN 'STUDENT'
                ELSE 'UNDEF'
            END AS role
        FROM users AS U
        LEFT JOIN teachers T ON T.user_id = U.id
        LEFT JOIN students S ON S.user_id = U.id
            WHERE true
        ";
    
        $searchConditions = '';
        if ($search) {
            $searchLower = strtolower($search);
    
            $hiddenVariants = ['i','d','e','n','hi', 'hid', 'id', 'idd', 'dd','dde', 'hidd', 'hidde', 'de', 'den', 'en'];
            $shownVariants = ['s','o','w','sh','ho','sho', 'show', 'wn', 'ow', 'own'];
    
            if ($searchLower === 'hidden' || in_array($searchLower, $hiddenVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 1 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } elseif ($searchLower === 'shown' || in_array($searchLower, $shownVariants)) {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= ($column === 'status') ? "$table.$column = 0 OR " : "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            } else {
                foreach ($allowedColumns as $column) {
                    $table = $columnTableMapping[$column];
                    $searchConditions .= "LOWER($table.$column) LIKE '%$searchLower%' OR ";
                }
            }
            $searchConditions = rtrim($searchConditions, ' OR ');
        }
    
        $sqlWithSortingAndSearch = $sqlTemplate;
    
        if ($searchConditions) {
            $sqlWithSortingAndSearch .= " AND $searchConditions";
        }

        $sqlWithSortingAndSearch .= " ORDER BY $sortColumn $sortOrder";

        $totalResults = DB::select("SELECT COUNT(*) as total FROM ($sqlWithSortingAndSearch) as countTable")[0]->total;
    
        $lastPage = ceil($totalResults / $perPage);
    
        $offset = ($page - 1) * $perPage;
    
        $rawResults = DB::select("$sqlWithSortingAndSearch LIMIT $perPage OFFSET $offset");

        $countQuery = "
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN role = 'TEACHER' THEN 1 ELSE 0 END) as countTeacher,
            SUM(CASE WHEN role = 'STUDENT' THEN 1 ELSE 0 END) as countStudent,
            SUM(CASE WHEN role = 'UNDEF' THEN 1 ELSE 0 END) as countUndef
        FROM ($sqlWithSortingAndSearch) as countTable
        ";
        
        $countResults = DB::select($countQuery)[0];
        
        $countTeacher = $countResults->countTeacher;
        $countStudent = $countResults->countStudent;
        $countUndef = $countResults->countUndef;
        
        return response()->json([
            'status' => 200,
            'users' => $rawResults,
            'pagination' => [
                'last_page' => $lastPage,
                'current_page' => $page,
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalResults),
                'total' => $totalResults,
            ],
            'counts' => [
                'teacher' => $countTeacher,
                'student' => $countStudent,
                'undef' => $countUndef,
            ],
        ]);
    }

    public static function update(Request $request, $id) {
        // Log::info('Incep actualizarea utilizatorului', ['user_id' => $id, 'request_data' => $request->all()]);
        $validator = Validator::make($request->all(), [
            'first_name' => 'nullable|string|max:191',
            'last_name' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Utilizatorul nu a fost găsit'
            ]);
        }

        $user->update($request->only(['first_name', 'last_name', 'email']));
        // Log::info('Finalizez actualizarea utilizatorului', ['user_id' => $id, 'updated_user' => $user->toArray()]);

        return response()->json([
            'status' => 200,
            'message' => 'Utilizatorul a fost actualizat cu succes',
            'user' => $user,
        ]);
    }

    public static function changePass(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required|string|min:8',
            'newPassword' => 'required|string|min:8',
            ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' =>  $validator->messages()
            ]);
        }
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => 'Utilizatorul nu a fost găsit'
            ]);
        }

        if (!Hash::check($request->oldPassword, $user->password)) {
            throw ValidationException::withMessages([
                'oldPassword' => ['The provided old password is incorrect.'],
            ]);
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();

        return response()->json(['message' => 'Password changed successfully'], 200);
    }
}
