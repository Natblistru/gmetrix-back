<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public static function index() {
        return User::all();
    }

    public static function show($id) {
        return User::find($id); 
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

}
