<?php

namespace App\Classes;

class Number {
    
    public static function addSpacing ($nums) {

        if (is_array($nums)) {
            foreach ($nums as $key => $value) {
                $nums[$key] = number_format($value , 0, '.', ' ');
            }
        }
        else {
              $nums = number_format($nums , 0, '.', ' ');
        }
        return $nums;
    }
}
