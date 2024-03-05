<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFunctions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("
            CREATE FUNCTION CalculateFeeByWeight(weight INT)
            RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE result INT;
                IF weight < 1000 THEN
                    SET result = 2000;
                ELSEIF weight >= 1000 AND weight < 3000 THEN
                    SET result = ROUND(weight / 1000) * 1000;
                ELSEIF weight = 3000 THEN
                    SET result = 4000;
                ELSEIF weight > 3000 AND weight <= 6000 THEN
                    SET result = 4000 + ROUND(weight / 1000) * 500;
                ELSE
                    SET result = 10000;
                END IF;
                RETURN result;
            END
        ");

        DB::statement("
            CREATE FUNCTION CalculateFeeByDistance(distance INT)
            RETURNS INT
            DETERMINISTIC
            BEGIN
                DECLARE fee INT;
                DECLARE test INT;
                IF ROUND(distance*1000) < 100 THEN 
                    SET fee = 1000;
                    SET test=1;
                ELSEIF ROUND(distance*1000) < 1000 THEN
                    SET fee = 1000 + ROUND(distance*10)*200;
                    SET test = 2;
                ELSEIF ROUND(distance*1000) = 1000 THEN
                    SET fee = 2500;
                    SET test = 3;
                ELSEIF ROUND(distance*1000) > 1000 THEN
                    SET fee = ROUND(distance*10)*100;
                END IF;
                RETURN fee;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP FUNCTION IF EXISTS CalculateFeeByWeight");
        DB::statement("DROP FUNCTION IF EXISTS CalculateFeeByDistance");
    }
}
