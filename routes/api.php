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

Route::group(["namespace"=> "App\Http\Controllers"], function() {
    Route::apiResource("chapters", ChapterController::class);
});
Route::group(["namespace"=> "App\Http\Controllers"], function() {
    Route::apiResource("subject_study_levels", SubjectStudyLevelController::class);
});

Route::get('/capitoleDisciplina', [ThemeLearningProgramController::class, "capitoleDisciplina"]);

Route::get('/disciplineani', [LearningProgramController::class, "disciplineAni"]);

Route::get('/teachertheme', [TeacherTopicController::class, "teacherTheme"]);

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