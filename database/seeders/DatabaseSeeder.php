<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            StudyLevelSeeder::class, 
            SubjectSeeder::class,
            SubjectStudyLevelSeeder::class,
            ChapterSeeder::class, 
            ThemeSeeder::class,
            LearningProgramSeeder::class,
            ThemeLearningProgramSeeder::class,
            UserSeeder::class,
            TeacherSeeder::class,
            TopicSeeder::class,
            TeacherTopicSeeder::class,
            SubtopicSeeder::class,
            StudentSeeder::class,
            TeacherPresentationSeeder::class,
            StudentSubopicProgressSeeder::class,
            FlipCardSeeder::class,
            SubtopicImageSeeder::class,
            EvaluationSeeder::class,
            EvaluationSubjectSeeder::class,
            EvaluationItemSeeder::class,
            EvaluationFormPageSeeder::class,
            EvaluationAnswerSeeder::class,
            EvaluationOptionSeeder::class,
            EvaluationAnswerOptionSeeder::class,
            StudentEvaluationAnswerSeeder::class,
            EvaluationSourceSeeder::class,
            EvaluationSubjectSourceSeeder::class,
            TestComlexitySeeder::class,
            FormativeTestSeeder::class,
            TestItemSeeder::class,
            TestItemOptionSeeder::class,
            TestItemColumnSeeder::class,
            FormativeTestItemSeeder::class,
            SummativeTestSeeder::class,
            SummativeTestItemSeeder::class,
            StudentFormativeTestResultSeeder::class,
            StudentSummativeTestResultSeeder::class,
            StudentFormativeTestOptionSeeder::class,
            StudentSummativeTestOptionSeeder::class,
            TagSeeder::class,
            VideoSeeder::class,
            TeacherThemeVideoSeeder::class,
            VideoBreakpointSeeder::class

        ]);
    }
}
