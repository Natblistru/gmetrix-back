<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\FlipCardController;
use App\Http\Controllers\SubtopicController;
use App\Http\Controllers\TestItemController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\TeacherTopicController;
use App\Http\Controllers\FormativeTestController;
use App\Http\Controllers\SubtopicImageController;
use App\Http\Controllers\SummativeTestController;
use App\Http\Controllers\TestComlexityController;
use App\Http\Controllers\EvaluationItemController;
use App\Http\Controllers\TestItemColumnController;
use App\Http\Controllers\TestItemOptionController;
use App\Http\Controllers\LearningProgramController;
use App\Http\Controllers\VideoBreakpointController;
use App\Http\Controllers\EvaluationAnswerController;
use App\Http\Controllers\EvaluationOptionController;
use App\Http\Controllers\EvaluationSourceController;
use App\Http\Controllers\EvaluationSubjectController;
use App\Http\Controllers\FormativeTestItemController;
use App\Http\Controllers\SubjectStudyLevelController;
use App\Http\Controllers\SummativeTestItemController;
use App\Http\Controllers\TeacherThemeVideoController;
use App\Http\Controllers\EvaluationFormPageController;
use App\Http\Controllers\ThemeLearningProgramController;
use App\Http\Controllers\EvaluationAnswerOptionController;
use App\Http\Controllers\StudentSubopicProgressController;
use App\Http\Controllers\EvaluationSubjectSourceController;
use App\Http\Controllers\StudentEvaluationAnswerController;
use App\Http\Controllers\StudentFormativeTestOptionController;
use App\Http\Controllers\StudentFormativeTestResultController;
use App\Http\Controllers\StudentSummativeTestOptionController;
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


Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, "register"]);
    Route::post('/login', [AuthController::class, "login"]);

    Route::post('/forgot-password', [AuthController::class, "forgot"]);       
    Route::post('/reset-password/{token}', [AuthController::class, "reset"]);    
});

// Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, "logout"]);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-user/{name}', [UserController::class, 'findUserByName']);
    Route::patch('/update-user/{id}', [UserController::class, "update"]);
    Route::patch('/change-password-user/{id}', [UserController::class, "changePass"]);
    Route::get('/view-myTopics', [TeacherTopicController::class, "index_teacher"]);
    Route::get('/view-mySubtopics', [SubtopicController::class, "index_teacher"]);
    Route::get('/view-myTests', [FormativeTestController::class, "index_teacher"]);
    Route::get('/all-myteachers', [TeacherController::class, "allTeachers"]);
    Route::post('/store-myteacherTopic', [TeacherTopicController::class, "store"]);
    Route::get('/edit-myteacherTopic/{id}', [TeacherTopicController::class, "edit"]);
    Route::post('/update-myteacherTopic/{id}', [TeacherTopicController::class, "update"]);    
    Route::post('/store-myvideo', [VideoController::class, "store"]);
    Route::get('/view-myvideos', [VideoController::class, "index"]);
    Route::get('/view-myteacherVideos', [TeacherThemeVideoController::class, "index"]);
    Route::get('/all-myteachervideo/{id}', [TeacherThemeVideoController::class, "allTeacherVideo"]);
    Route::post('/store-myteacherVideo', [TeacherThemeVideoController::class, "store"]);
    Route::post('/store-mybreakpoint', [VideoBreakpointController::class, "store"]);
    Route::get('/all-myteacher-topics', [TeacherTopicController::class, "allTeacherTopics"]);
    Route::post('/store-mysubtopic', [SubtopicController::class, "store"]);
    Route::get('/get-mysubtopic/{name}', [SubtopicController::class, 'findSubtopicByName']);
    Route::get('/edit-mysubtopic/{id}', [SubtopicController::class, "edit"]);
    Route::post('/update-mysubtopic/{id}', [SubtopicController::class, "update"]);    
    Route::post('/store-mysubtopic-image', [SubtopicImageController::class, "store"]);
    Route::post('/store-myflip-card', [FlipCardController::class, "store"]);
    Route::post('/store-myformative-test', [FormativeTestController::class, "store"]);
    Route::get('/edit-myformative-test/{id}', [FormativeTestController::class, "edit"]);
    Route::post('/update-myformative-test/{id}', [FormativeTestController::class, "update"]);
    Route::post('/store-mytest-item', [TestItemController::class, "store"]);
    Route::get('/get-mytestitem/{task}', [TestItemController::class, 'findTestItemByTask']);
    Route::post('/store-mytest-item-option', [TestItemOptionController::class, "store"]);
    Route::post('/store-myformative-test-item', [FormativeTestItemController::class, "store"]);
    Route::post('/store-mytest-item-column', [TestItemColumnController::class, "store"]);
    Route::post('/store-mytag', [TagController::class, "store"]);

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

    Route::get('/view-evaluations', [EvaluationController::class, "index"]);
    Route::post('/store-evaluation', [EvaluationController::class, "store"]);
    Route::get('/edit-evaluation/{id}', [EvaluationController::class, "edit"]);
    Route::post('/update-evaluation/{id}', [EvaluationController::class, "update"]);

    Route::get('/view-evaluation-subjects', [EvaluationSubjectController::class, "index"]);
    Route::post('/store-evaluation-subject', [EvaluationSubjectController::class, "store"]);
    Route::get('/edit-evaluation-subject/{id}', [EvaluationSubjectController::class, "edit"]);
    Route::post('/update-evaluation-subject/{id}', [EvaluationSubjectController::class, "update"]);

    Route::get('/view-evaluation-source', [EvaluationSourceController::class, "index"]);
    Route::post('/store-evaluation-source', [EvaluationSourceController::class, "store"]);
    Route::get('/edit-evaluation-source/{id}', [EvaluationSourceController::class, "edit"]);
    Route::post('/update-evaluation-source/{id}', [EvaluationSourceController::class, "update"]);

    Route::get('/view-evaluation-subject-sourse', [EvaluationSubjectSourceController::class, "index"]);
    Route::post('/store-evaluation-subject-source', [EvaluationSubjectSourceController::class, "store"]);
    Route::get('/edit-evaluation-subject-source/{id}', [EvaluationSubjectSourceController::class, "edit"]);
    Route::post('/update-evaluation-subject-source/{id}', [EvaluationSubjectSourceController::class, "update"]);

    Route::get('/view-evaluation-item', [EvaluationItemController::class, "index"]);
    Route::post('/store-evaluation-item', [EvaluationItemController::class, "store"]);
    Route::get('/edit-evaluation-item/{id}', [EvaluationItemController::class, "edit"]);
    Route::post('/update-evaluation-item/{id}', [EvaluationItemController::class, "update"]);

    Route::get('/view-evaluation-answer', [EvaluationAnswerController::class, "index"]);
    Route::post('/store-evaluation-answer', [EvaluationAnswerController::class, "store"]);
    Route::get('/edit-evaluation-answer/{id}', [EvaluationAnswerController::class, "edit"]);
    Route::post('/update-evaluation-answer/{id}', [EvaluationAnswerController::class, "update"]);

    Route::get('/view-evaluation-option', [EvaluationOptionController::class, "index"]);
    Route::post('/store-evaluation-option', [EvaluationOptionController::class, "store"]);
    Route::get('/edit-evaluation-option/{id}', [EvaluationOptionController::class, "edit"]);
    Route::post('/update-evaluation-option/{id}', [EvaluationOptionController::class, "update"]);

    Route::get('/view-evaluation-answer-option', [EvaluationAnswerOptionController::class, "index"]);
    Route::post('/store-evaluation-answer-option', [EvaluationAnswerOptionController::class, "store"]);
    Route::get('/edit-evaluation-answer-option/{id}', [EvaluationAnswerOptionController::class, "edit"]);
    Route::post('/update-evaluation-answer-option/{id}', [EvaluationAnswerOptionController::class, "update"]);

    Route::get('/view-evaluation-form-page', [EvaluationFormPageController::class, "index"]);
    Route::post('/store-evaluation-form-page', [EvaluationFormPageController::class, "store"]);
    Route::get('/edit-evaluation-form-page/{id}', [EvaluationFormPageController::class, "edit"]);
    Route::post('/update-evaluation-form-page/{id}', [EvaluationFormPageController::class, "update"]);

    Route::get('/view-subtopic', [SubtopicController::class, "index"]);
    Route::post('/store-subtopic', [SubtopicController::class, "store"]);
    Route::get('/edit-subtopic/{id}', [SubtopicController::class, "edit"]);
    Route::post('/update-subtopic/{id}', [SubtopicController::class, "update"]);

    Route::get('/view-subtopic-image', [SubtopicImageController::class, "index"]);
    Route::post('/store-subtopic-image', [SubtopicImageController::class, "store"]);
    Route::get('/edit-subtopic-image/{id}', [SubtopicImageController::class, "edit"]);
    Route::post('/update-subtopic-image/{id}', [SubtopicImageController::class, "update"]);

    Route::get('/view-flip-card', [FlipCardController::class, "index"]);
    Route::post('/store-flip-card', [FlipCardController::class, "store"]);
    Route::get('/edit-flip-card/{id}', [FlipCardController::class, "edit"]);
    Route::post('/update-flip-card/{id}', [FlipCardController::class, "update"]);

    Route::get('/view-test-item', [TestItemController::class, "index"]);
    Route::post('/store-test-item', [TestItemController::class, "store"]);
    Route::get('/edit-test-item/{id}', [TestItemController::class, "edit"]);
    Route::post('/update-test-item/{id}', [TestItemController::class, "update"]);

    Route::get('/view-test-item-column', [TestItemColumnController::class, "index"]);
    Route::post('/store-test-item-column', [TestItemColumnController::class, "store"]);
    Route::get('/edit-test-item-column/{id}', [TestItemColumnController::class, "edit"]);
    Route::post('/update-test-item-column/{id}', [TestItemColumnController::class, "update"]);

    Route::get('/view-test-item-option', [TestItemOptionController::class, "index"]);
    Route::post('/store-test-item-option', [TestItemOptionController::class, "store"]);
    Route::get('/edit-test-item-option/{id}', [TestItemOptionController::class, "edit"]);
    Route::post('/update-test-item-option/{id}', [TestItemOptionController::class, "update"]);

    Route::get('/view-formative-test', [FormativeTestController::class, "index"]);
    Route::post('/store-formative-test', [FormativeTestController::class, "store"]);
    Route::get('/edit-formative-test/{id}', [FormativeTestController::class, "edit"]);
    Route::post('/update-formative-test/{id}', [FormativeTestController::class, "update"]);

    Route::get('/view-formative-test-item', [FormativeTestItemController::class, "index"]);
    Route::post('/store-formative-test-item', [FormativeTestItemController::class, "store"]);
    Route::get('/edit-formative-test-item/{id}', [FormativeTestItemController::class, "edit"]);
    Route::post('/update-formative-test-item/{id}', [FormativeTestItemController::class, "update"]);

    Route::get('/view-summative-test', [SummativeTestController::class, "index"]);
    Route::post('/store-summative-test', [SummativeTestController::class, "store"]);
    Route::get('/edit-summative-test/{id}', [SummativeTestController::class, "edit"]);
    Route::post('/update-summative-test/{id}', [SummativeTestController::class, "update"]);

    Route::get('/view-summative-test-item', [SummativeTestItemController::class, "index"]);
    Route::post('/store-summative-test-item', [SummativeTestItemController::class, "store"]);
    Route::get('/edit-summative-test-item/{id}', [SummativeTestItemController::class, "edit"]);
    Route::post('/update-summative-test-item/{id}', [SummativeTestItemController::class, "update"]);

 
    Route::get('/all-subtopics', [SubtopicController::class, "allSubtopics"]);
    Route::get('/all-tests', [FormativeTestItemController::class, "allTests"]);
    Route::get('/all-videos', [VideoController::class, "allvideos"]);
    Route::get('/all-users', [UserController::class, "allUsers"]);

    Route::get('/all-evaluations', [EvaluationController::class, "allEvaluations"]);
    Route::get('/all-evaluation-subjects', [EvaluationSubjectController::class, "allEvaluationSubjects"]);   
    Route::get('/all-evaluation-sources', [EvaluationSourceController::class, "allEvaluationSources"]);   
    Route::get('/all-evaluation-items', [EvaluationItemController::class, "allEvaluationItems"]);
    Route::get('/all-evaluation-answers', [EvaluationAnswerController::class, "allEvaluationAnswers"]);
    Route::get('/all-evaluation-options', [EvaluationOptionController::class, "allEvaluationOptions"]);

    Route::get('/all-test-items', [TestItemController::class, "allTestItems"]);
    Route::get('/all-formative-tests', [FormativeTestController::class, "allFormativeTests"]);
    Route::get('/all-summative-tests', [SummativeTestController::class, "allSummativeTests"]);


});


Route::get('/all-subject-study-level', [SubjectStudyLevelController::class, "allSubjectStudyLevel"]);
Route::get('/all-learningPrograms', [LearningProgramController::class, "allLearningPrograms"]);
Route::get('/all-themeLearningPrograms', [ThemeLearningProgramController::class, "allThemeLearningPrograms"]);
Route::get('/all-chapters', [ChapterController::class, "allChapters"]);
Route::get('/all-themes', [ThemeController::class, "allThemes"]);
Route::get('/all-topics', [TopicController::class, "allTopics"]);
Route::get('/all-teachers', [TeacherController::class, "allTeachers"]);
Route::get('all-teacher-topics', [TeacherTopicController::class, "allTeacherTopics"]);
Route::post('/all-tags', [TagController::class, "allTags"]);
Route::post('/get-posts-by-tags', [TagController::class, "getPostsByTags"]);
Route::get('/all-test-complexities', [TestComlexityController::class, "allTestComplexities"]);




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

Route::post('/update-student-evaluation-answers', [StudentEvaluationAnswerController::class, "update"]);

Route::post('/student-formative-test-options', [StudentFormativeTestOptionController::class, "store"]);

Route::post('/student-summative-test-options', [StudentSummativeTestOptionController::class, "store"]);

Route::post('/student-formative-test-results', [StudentFormativeTestResultController::class, "store"]);

Route::post('/student-summative-test-results', [StudentSummativeTestResultController::class, "store"]);

Route::post('/student-formative-test-score', [StudentFormativeTestResultController::class, 'getStudentFormativeTestScore']);

Route::post('/student-evaluation-results', [StudentEvaluationAnswerController::class, "getStudentEvaluationResults"]);

Route::post('/update-student-formative-test-result', [StudentFormativeTestResultController::class, "update"]);

Route::post('/update-student-formative-test-option', [StudentFormativeTestOptionController::class, "update"]);

Route::patch('/update-student-summative-test-result', [StudentSummativeTestResultController::class, "update"]);