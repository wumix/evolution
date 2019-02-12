<?php

namespace App\Classes;

class Land {
     
    public static function getLandPrice ($land) {

        $price = (1E-08 * pow($land,3)) + (9E-11 * pow($land,2)) + (0.1 * $land) + 0.0643;
        return $price;
    }
    
    public static function getAvalibleLand ($gold, $land) {
        $price = 0;
        $avalibleLand = 0;
        $price = self::getLandPrice($land);
        while ($price <= $gold) {
            $avalibleLand++;
            $price += self::getLandPrice($land + $avalibleLand);
        }
        return $avalibleLand;
    }
}
