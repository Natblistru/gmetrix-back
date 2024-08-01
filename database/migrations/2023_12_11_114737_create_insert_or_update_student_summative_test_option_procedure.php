<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateInsertOrUpdateStudentSummativeTestOptionProcedure extends Migration
{
    public function up()
    {
        $procedure = <<<SQL
        CREATE PROCEDURE InsertOrUpdateStudentSummativeTestOption(
            p_student_id bigint,
            p_summative_test_id bigint,
            p_test_item_id bigint,
            p_score decimal(25,2),
            p_option varchar(500),
            p_type varchar(50),
            p_explanation varchar(5000)
        )
        BEGIN
            DECLARE existing_row_count int;
            DECLARE existing_summative_test_item_id bigint;
            DECLARE existing_test_item_option_id bigint;


            -- Verificăm dacă există un rând cu același student_id și evaluation_answer_option_id
            SELECT COUNT(*) INTO existing_row_count
            FROM student_summative_test_options SFTO
            INNER JOIN summative_test_items FTI ON SFTO.summative_test_item_id = FTI.id
            INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
            WHERE student_id = p_student_id
            AND FTI.summative_test_id = p_summative_test_id
            AND FTI.test_item_id = p_test_item_id
            AND TIO.option = p_option;

            -- Dacă există, actualizăm rândul
            IF existing_row_count > 0 THEN

                UPDATE student_summative_test_options AS SFTO
                JOIN summative_test_items FTI ON SFTO.summative_test_item_id = FTI.id
                INNER JOIN test_item_options TIO ON SFTO.test_item_option_id = TIO.id
                SET SFTO.score = p_score,
                    SFTO.updated_at = CURRENT_TIMESTAMP
                WHERE student_id = p_student_id
                AND FTI.summative_test_id = p_summative_test_id
                AND FTI.test_item_id = p_test_item_id
                AND TIO.option = p_option;
            ELSE
                -- Dacă nu există, obtinem existing_summative_test_item_id
                SELECT FTI.id INTO existing_summative_test_item_id 
                FROM summative_test_items FTI 
                WHERE FTI.summative_test_id = p_summative_test_id
                AND FTI.test_item_id = p_test_item_id;

                -- Si obtinem existing_test_item_option_id
                SELECT TIO.id INTO existing_test_item_option_id 
                FROM test_item_options TIO 
                WHERE TIO.option = p_option
                AND TIO.test_item_id = p_test_item_id;

                -- Si adăugăm un nou rând în student_summative_test_options
                INSERT INTO student_summative_test_options (student_id, summative_test_item_id, test_item_option_id, score, created_at, updated_at)
                VALUES (p_student_id, existing_summative_test_item_id, existing_test_item_option_id, p_score, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);
            END IF;    
        END; 
        SQL;

        DB::unprepared($procedure);
    }

    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS InsertOrUpdateStudentSummativeTestOption');
    }
}