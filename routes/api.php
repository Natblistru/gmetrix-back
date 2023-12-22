<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThemeLearningProgramController;
use App\Http\Controllers\LearningProgramController;
use App\Http\Controllers\TeacherTopicController;
use App\Http\Controllers\TeacherThemeVideoController;
use App\Http\Controllers\EvaluationSubjectController;
use App\Http\Controllers\FormativeTestController;
use App\Http\Controllers\SummativeTestController;
use App\Http\Controllers\StudentSubopicProgressController;
use App\Http\Controllers\StudentEvaluationAnswerController;
use App\Http\Controllers\StudentFormativeTestOptionController;
use App\Http\Controllers\StudentSummativeTestOptionController;
use App\Http\Controllers\StudentFormativeTestResultController;
use App\Http\Controllers\StudentSummativeTestResultController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\VideoBreakpointController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\TeacherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, "register"]);
    Route::post('/login', [AuthController::class, "login"]);

    Route::post('/forgot-password', [AuthController::class, "forgot"]);       
    Route::post('/reset-password/{token}', [AuthController::class, "reset"]);    
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, "logout"]);
});

Route::middleware('auth:sanctum','isAPIAdmin')->group(function () {
    // Route::middleware('auth:sanctum')->group(function () {
    Route::get('/checkingAuthenticated', function () {
        return response()->json(['message'=>'You are in', 'status'=>200],200);
    });

    Route::post('/store-video', [VideoController::class, "store"]);
    Route::get('/view-videos', [VideoController::class, "index"]);
    Route::get('/edit-video/{id}', [VideoController::class, "edit"]); 
    Route::post('/update-video/{id}', [VideoController::class, "update"]); 

    Route::post('/store-breakpoint', [VideoBreakpointController::class, "store"]);
    Route::get('/view-breakpoints', [VideoBreakpointController::class, "index"]);
    Route::get('/edit-breakpoint/{id}', [VideoBreakpointController::class, "edit"]);
    Route::post('/update-breakpoint/{id}', [VideoBreakpointController::class, "update"]); 

    Route::get('/view-teacherTopics', [TeacherTopicController::class, "index"]);
    Route::post('/store-teacherTopic', [TeacherTopicController::class, "store"]);
    Route::get('/edit-teacherTopic/{id}', [TeacherTopicController::class, "edit"]);
    Route::post('/update-teacherTopic/{id}', [TeacherTopicController::class, "update"]);

    Route::get('/view-teacherVideos', [TeacherThemeVideoController::class, "index"]);
    Route::post('/store-teacherVideo', [TeacherThemeVideoController::class, "store"]);
    Route::get('/edit-teacherVideo/{id}', [TeacherThemeVideoController::class, "edit"]);
    Route::post('/update-teacherVideo/{id}', [TeacherThemeVideoController::class, "update"]);

    Route::get('/all-learningPrograms', [LearningProgramController::class, "allLearningPrograms"]);
    Route::get('/all-themeLearningPrograms', [ThemeLearningProgramController::class, "allThemeLearningPrograms"]);
    Route::get('/all-topics', [TopicController::class, "allTopics"]);
    Route::get('/all-videos', [VideoController::class, "allvideos"]);
    Route::get('/all-teachers', [TeacherController::class, "allTeachers"]);
});





Route::group(["namespace"=> "App\Http\Controllers"], function() {
    Route::apiResource("chapters", ChapterController::class);
});
Route::group(["namespace"=> "App\Http\Controllers"], function() {
    Route::apiResource("subject_study_levels", SubjectStudyLevelController::class);
});

Route::get('/capitoleDisciplina', [ThemeLearningProgramController::class, "capitoleDisciplina"]);

Route::get('/disciplineani', [LearningProgramController::class, "disciplineAni"]);

Route::get('/teachertheme', [TeacherTopicController::class, "teacherTheme"]);

Route::get('/teachers-with-themes', [TeacherTopicController::class, 'getAllTeachersWithThemes']);

Route::get('/teacherthemevideo', [TeacherThemeVideoController::class, "teacherThemeVideo"]);

Route::get('/themeevaluations', [EvaluationSubjectController::class, "themeEvaluations"]);

Route::get('/themeevaluation1', [EvaluationSubjectController::class, "themeEvaluation1"]);

Route::get('/themeevaluation2', [EvaluationSubjectController::class, "themeEvaluation2"]);

Route::get('/themeevaluation3', [EvaluationSubjectController::class, "themeEvaluation3"]);

Route::get('/formativetest', [FormativeTestController::class, "formativeTest"]);

Route::get('/summativetest', [SummativeTestController::class, "summativeTest"]);

Route::post('/student-subtopic-progress', [StudentSubopicProgressController::class, "store"]);

Route::post('/student-evaluation-answers', [StudentEvaluationAnswerController::class, "store"]);

Route::post('/student-formative-test-options', [StudentFormativeTestOptionController::class, "store"]);

Route::post('/student-summative-test-options', [StudentSummativeTestOptionController::class, "store"]);

Route::post('/student-formative-test-results', [StudentFormativeTestResultController::class, "store"]);

Route::post('/student-summative-test-results', [StudentSummativeTestResultController::class, "store"]);



