<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCalculateByDistanceFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("
            CREATE FUNCTION CalculateFeeByDistance(distance FLOAT)
            RETURNS INT DETERMINISTIC
            BEGIN
                DECLARE kilometer INT DEFAULT 1000;
                DECLARE per100Meter INT DEFAULT 100;
                DECLARE result INT DEFAULT 0;
                DECLARE distanceInMeter INT;
                SET distanceInMeter = ROUND(distance * kilometer);
                IF distanceInMeter < per100Meter THEN
                    SET result = 500;
                ELSEIF distanceInMeter >= per100Meter AND distanceInMeter < kilometer THEN
                    SET result = ROUND(distanceInMeter / per100Meter) * 500;
                ELSEIF ROUND(distance) = 1 THEN
                    SET result = 5000;
                ELSEIF ROUND(distance) > 1 AND ROUND(distance) <= 3 THEN
                    SET result = 5000 + ROUND(distance * 2000);
                ELSE
                    SET result = 15000;
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
