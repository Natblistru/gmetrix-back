<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInsertOrUpdateStudentSummativeTestResultProcedure extends Migration
{
    public function up()
    {
        $procedure = <<<SQL
        CREATE PROCEDURE InsertOrUpdateStudentSummativeTestResult(
            p_student_id bigint,
            p_summative_test_id bigint,
            p_test_item_id bigint,
            p_type varchar(50)
        )
        BEGIN
            DECLARE existing_row_result int;
            DECLARE existing_row_option int;
            DECLARE existing_answers int;
            DECLARE average_score decimal(25,2);
            DECLARE existing_summative_test_item_id bigint;

            SELECT COUNT(*) INTO existing_row_option
            FROM student_summative_test_options SFTO
            INNER JOIN summative_test_items FTI ON SFTO.summative_test_item_id = FTI.id
            WHERE SFTO.student_id = p_student_id
            AND FTI.summative_test_id = p_summative_test_id
            AND FTI.test_item_id = p_test_item_id;

            IF existing_row_option > 0 THEN

                SELECT COUNT(*) INTO existing_row_result
                FROM student_summative_test_results SFTR
                INNER JOIN summative_test_items FTI ON SFTR.summative_test_item_id = FTI.id
                WHERE student_id = p_student_id
                AND FTI.summative_test_id = p_summative_test_id
                AND FTI.test_item_id = p_test_item_id;

                IF p_type = 'check' THEN
                    SELECT AVG(SSTO.score) INTO average_score
                    FROM student_summative_test_options SSTO 
                    INNER JOIN summative_test_items FTI ON SSTO.summative_test_item_id = FTI.id
                    WHERE FTI.summative_test_id = p_summative_test_id
                    AND SSTO.student_id = p_student_id
                    AND FTI.test_item_id = p_test_item_id;
                ELSEIF p_type = 'snap' THEN    
                    SELECT COUNT(DISTINCT(TIO.id)) INTO existing_answers
                    FROM student_summative_test_options SSTO 
                    INNER JOIN summative_test_items FTI ON SSTO.summative_test_item_id = FTI.id
                    INNER JOIN test_item_options TIO ON TIO.test_item_id = FTI.test_item_id AND TIO.correct = 1
                    WHERE FTI.summative_test_id = p_summative_test_id
                    AND SSTO.student_id = p_student_id
                    AND FTI.test_item_id = p_test_item_id;

                    IF existing_answers > 0 THEN 
                        SELECT (SUM(SSTO.score) / existing_answers) INTO average_score
                        FROM student_summative_test_options SSTO 
                        INNER JOIN summative_test_items FTI ON SSTO.summative_test_item_id = FTI.id
                        WHERE FTI.summative_test_id = p_summative_test_id
                        AND SSTO.student_id = p_student_id
                        AND FTI.test_item_id = p_test_item_id;
                    ELSE 
                        SET average_score = 0;
                    END IF;
                END IF;

                IF existing_row_result > 0 THEN

                    UPDATE student_summative_test_results AS SFTR
                    JOIN summative_test_items FTI ON SFTR.summative_test_item_id = FTI.id
                    SET SFTR.score = average_score,
                        SFTR.updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND FTI.summative_test_id = p_summative_test_id
                    AND FTI.test_item_id = p_test_item_id;
                ELSE
                    -- Dacă nu există, obtinem existing_summative_test_item_id
                    SELECT FTI.id INTO existing_summative_test_item_id 
                    FROM summative_test_items FTI 
                    WHERE FTI.summative_test_id = p_summative_test_id
                    AND FTI.test_item_id = p_test_item_id;

                    -- Si adăugăm un nou rând în student_summative_test_results
                    INSERT INTO student_summative_test_results (student_id, summative_test_item_id, score, created_at, updated_at)
                    VALUES (p_student_id, existing_summative_test_item_id, average_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF;
            END IF;              
        END; 
        SQL;

        DB::unprepared($procedure);
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertOrUpdateStudentSummativeTestResult');
    }
}