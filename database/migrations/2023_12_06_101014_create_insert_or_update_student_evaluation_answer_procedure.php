<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInsertOrUpdateStudentEvaluationAnswerProcedure extends Migration
{
    public function up()
    {
        $procedure = <<<SQL
        CREATE PROCEDURE InsertOrUpdateStudentEvaluationAnswer(
            p_student_id bigint,
            p_evaluation_answer_option_id bigint,
            p_points int,
            p_answer_id bigint
        )
        BEGIN
            DECLARE existing_row_count int;
            DECLARE existing_evaluation_answer_option_id bigint;

            -- Verificăm dacă există un rând cu același student_id și evaluation_answer_option_id
            SELECT COUNT(*) INTO existing_row_count
            FROM student_evaluation_answers
            WHERE student_id = p_student_id
            AND evaluation_answer_option_id = p_evaluation_answer_option_id;

            -- Dacă există, actualizăm rândul
            IF existing_row_count > 0 THEN
                UPDATE student_evaluation_answers
                SET points = p_points,
                    updated_at = CURRENT_TIMESTAMP
                WHERE student_id = p_student_id
                AND evaluation_answer_option_id = p_evaluation_answer_option_id;
            ELSE
                -- Dacă nu există, verificăm dacă există un rând cu același student_id și answer_id în Evaluation_answer_option
                SELECT SEA.evaluation_answer_option_id INTO existing_evaluation_answer_option_id 
                FROM student_evaluation_answers SEA 
                INNER JOIN evaluation_answer_options EAO ON SEA.evaluation_answer_option_id = EAO.id
                WHERE EAO.evaluation_answer_id = p_answer_id
                AND SEA.student_id = p_student_id;

                -- Dacă există, actualizăm rândul
                IF existing_evaluation_answer_option_id IS NOT NULL THEN
                    UPDATE student_evaluation_answers
                    SET points = p_points,
                        evaluation_answer_option_id = p_evaluation_answer_option_id,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND evaluation_answer_option_id = existing_evaluation_answer_option_id;
                ELSE
                    -- Dacă nu există, adăugăm un nou rând în student_evaluation_answers
                    INSERT INTO student_evaluation_answers (student_id, evaluation_answer_option_id, points, created_at, updated_at)
                    VALUES (p_student_id, p_evaluation_answer_option_id, p_points, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF; 
            END IF;
        END; 
        SQL;

        DB::unprepared($procedure);
    }

    public function down()
    {
        // Puteți implementa logica de rollback aici, dacă este necesar
    }
}