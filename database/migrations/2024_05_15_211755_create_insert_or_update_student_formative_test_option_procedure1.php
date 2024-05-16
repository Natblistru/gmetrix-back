<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInsertOrUpdateStudentFormativeTestOptionOptionProcedure extends Migration
{
    public function up()
    {
        $procedure = <<<SQL
        CREATE PROCEDURE InsertOrUpdateStudentFormativeTestOptionOption(
            p_student_id bigint,
            p_formative_test_id bigint,
            p_test_item_id bigint,
            p_score decimal(25,2),
            p_option varchar(500),
            p_type varchar(50),
            p_explanation varchar(5000)
        )
        BEGIN
            DECLARE existing_row_count int;
            DECLARE existing_formative_test_item_id bigint;
            DECLARE existing_test_item_option_id bigint;

            IF p_type = 'quiz' THEN
                -- Verificăm dacă există un rând cu același student_id și evaluation_answer_option_id
                SELECT COUNT(*) INTO existing_row_count
                FROM student_formative_test_options SFTO
                INNER JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                WHERE student_id = p_student_id
                AND FTI.formative_test_id = p_formative_test_id
                AND FTI.test_item_id = p_test_item_id;

                -- Dacă există, actualizăm rândul
                IF existing_row_count > 0 THEN

                    -- Obtinem existing_test_item_option_id
                    SELECT TIO.id INTO existing_test_item_option_id 
                    FROM test_item_options TIO 
                    WHERE TIO.option = p_option
                    AND TIO.test_item_id = p_test_item_id;

                    UPDATE student_formative_test_options AS SFTO
                    JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                    SET SFTO.score = p_score,
                        SFTO.test_item_option_id = existing_test_item_option_id,
                        SFTO.updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;
                ELSE
                    -- Dacă nu există, obtinem existing_formative_test_item_id
                    SELECT FTI.id INTO existing_formative_test_item_id 
                    FROM formative_test_items FTI 
                    WHERE FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;

                    -- Obtinem existing_test_item_option_id
                    SELECT TIO.id INTO existing_test_item_option_id 
                    FROM test_item_options TIO 
                    WHERE TIO.option = p_option
                    AND TIO.test_item_id = p_test_item_id;

                    -- Si adăugăm un nou rând în student_formative_test_options
                    INSERT INTO student_formative_test_options (student_id, formative_test_item_id, test_item_option_id, score, created_at, updated_at)
                    VALUES (p_student_id, existing_formative_test_item_id, existing_test_item_option_id, p_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF;

                -- Inregistram RESULTS (doar la quiz - 1 rand, la restul - procedura aparte)
                SELECT COUNT(*) INTO existing_row_count
                FROM student_formative_test_results SFTR
                INNER JOIN formative_test_items FTI ON SFTR.formative_test_item_id = FTI.id
                WHERE student_id = p_student_id
                AND FTI.formative_test_id = p_formative_test_id
                AND FTI.test_item_id = p_test_item_id;

                -- Dacă există, actualizăm rândul
                IF existing_row_count > 0 THEN

                    UPDATE student_formative_test_results AS SFTR
                    JOIN formative_test_items FTI ON SFTR.formative_test_item_id = FTI.id
                    SET SFTR.score = p_score,
                        SFTR.updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;
                ELSE
                    -- Dacă nu există, obtinem existing_formative_test_item_id
                    SELECT FTI.id INTO existing_formative_test_item_id 
                    FROM formative_test_items FTI 
                    WHERE FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;

                    -- Si adăugăm un nou rând în student_formative_test_options
                    INSERT INTO student_formative_test_results (student_id, formative_test_item_id, score, created_at, updated_at)
                    VALUES (p_student_id, existing_formative_test_item_id, p_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF;

            ELSEIF p_type = 'check' THEN
                -- Verificăm dacă există un rând cu același student_id și evaluation_answer_option_id
                SELECT COUNT(*) INTO existing_row_count
                FROM student_formative_test_options SFTO
                INNER JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
                WHERE student_id = p_student_id
                AND FTI.formative_test_id = p_formative_test_id
                AND FTI.test_item_id = p_test_item_id
                AND TIO.option = p_option;

                -- Dacă există, actualizăm rândul
                IF existing_row_count > 0 THEN
                    UPDATE student_formative_test_options AS SFTO
                    JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                    INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
                    SET SFTO.score = p_score,
                        SFTO.updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id
                    AND TIO.option = p_option;
                ELSE
                    -- Dacă nu există, obtinem existing_formative_test_item_id
                    SELECT FTI.id INTO existing_formative_test_item_id 
                    FROM formative_test_items FTI 
                    WHERE FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;

                    -- Si obtinem existing_test_item_option_id
                    SELECT TIO.id INTO existing_test_item_option_id 
                    FROM test_item_options TIO 
                    WHERE TIO.option = p_option
                    AND TIO.test_item_id = p_test_item_id;

                    -- Si adăugăm un nou rând în student_formative_test_options
                    INSERT INTO student_formative_test_options (student_id, formative_test_item_id, test_item_option_id, score, created_at, updated_at)
                    VALUES (p_student_id, existing_formative_test_item_id, existing_test_item_option_id, p_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF;
            ELSEIF p_type = 'snap' THEN
                -- Verificăm dacă există un rând cu același student_id și test_item_id si explanation
                SELECT COUNT(*) INTO existing_row_count
                FROM student_formative_test_options SFTO
                INNER JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
                WHERE student_id = p_student_id
                AND FTI.formative_test_id = p_formative_test_id
                AND FTI.test_item_id = p_test_item_id
                AND TIO.explanation = p_explanation;

                -- Dacă există, actualizăm rândul
                IF existing_row_count > 0 THEN

                    -- Obtinem existing_test_item_option_id
                    SELECT TIO.id INTO existing_test_item_option_id 
                    FROM test_item_options TIO 
                    WHERE TIO.option = p_option
                    AND TIO.test_item_id = p_test_item_id;

                    UPDATE student_formative_test_options AS SFTO
                    JOIN formative_test_items FTI ON SFTO.formative_test_item_id = FTI.id
                    INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
                    SET SFTO.score = p_score,
                        SFTO.test_item_option_id = existing_test_item_option_id,
                        SFTO.updated_at = CURRENT_TIMESTAMP
                    WHERE student_id = p_student_id
                    AND FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id
                    AND TIO.explanation = p_explanation;
                ELSE
                    -- Dacă nu există, obtinem existing_formative_test_item_id
                    SELECT FTI.id INTO existing_formative_test_item_id 
                    FROM formative_test_items FTI 
                    WHERE FTI.formative_test_id = p_formative_test_id
                    AND FTI.test_item_id = p_test_item_id;

                    -- Si obtinem existing_test_item_option_id
                    SELECT TIO.id INTO existing_test_item_option_id 
                    FROM test_item_options TIO 
                    WHERE TIO.option = p_option
                    AND TIO.test_item_id = p_test_item_id;

                    -- Si adăugăm un nou rând în student_formative_test_options
                    INSERT INTO student_formative_test_options (student_id, formative_test_item_id, test_item_option_id, score, created_at, updated_at)
                    VALUES (p_student_id, existing_formative_test_item_id, existing_test_item_option_id, p_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
                END IF;
            END IF;                
        END; 
        SQL;

        DB::unprepared($procedure);
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertOrUpdateStudentFormativeTestOptionOptions');
    }
}