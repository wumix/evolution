<?php

namespace App\Classes;

class Buildings {
     
    public static function getBonus ($buildings, $workers) {
        if($workers > 0) {
        $bonus = ($buildings / ($workers / 10)) * 0.4;
        if($bonus > 0.4) {
            $bonus = 0.4;
        }
        return $bonus;
        }
        else {
            return 0;
        }
    }
}
