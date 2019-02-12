<?php

namespace App\Classes;

class Race {
    
    public static function getCitizensPerLand ($race) {
        switch ($race) {
            case 'human': 
                return 3.3;
            case 'elf':
                return 2.8;
            case 'dwarf':
                return 3.2;
            case 'halfling':
                return 3.2;
            case 'orc':
                return 3.1;
            case 'fairy':
                return 1.9;
            case 'undead':
                return 5.9;
            case 'demon':
                return 2.1; 
        }
    }
    
    public static function getSoldier1 ($race) {
        switch ($race) {
            case 'human': 
                return 'Rytíř';
            case 'elf':
                return 'Ostrostřelec';
            case 'dwarf':
                return 'Berserker';
            case 'halflin':
                return 'Placeholder';
            case 'orc':
                return 'Skurut';
            case 'fairy':
                return 'Placeholder';
            case 'undead':
                return 'Kostlivec';
            case 'demon':
                return 'Placeholder'; 
        }
    }
    
    public static function getSoldier2 ($race) {
        switch ($race) {
            case 'human': 
                return 'Šermíř';
            case 'elf':
                return 'Hraničář';
            case 'dwarf':
                return 'Sekerník';
            case 'halflin':
                return 'Placeholder';
            case 'orc':
                return 'Vrk';
            case 'fairy':
                return 'Placeholder';
            case 'undead':
                return 'Zombie';
            case 'demon':
                return 'Placeholder'; 
        }
    }
}
