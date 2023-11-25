<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCalculateByWeightFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
        CREATE FUNCTION CalculateFeeByWeight(weight INT)
        RETURNS INT DETERMINISTIC
        BEGIN
            DECLARE result INT;
            IF weight < 1000 THEN
                SET result = 3000;
            ELSEIF weight >= 1000 AND weight < 3000 THEN
                SET result = ROUND(weight / 1000) * 9000;
            ELSEIF weight = 3000 THEN
                SET result = 35000;
            ELSEIF weight > 3000 AND weight <= 6000 THEN
                SET result = 35000 + ROUND(weight / 1000) * 3000;
            ELSE
                SET result = 50000;
            END IF;
            RETURN result;
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
        DB::unprepared('DROP FUNCTION IF EXISTS CalculateFeeByWeight');
    }
}
