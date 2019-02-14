<?php

namespace App\Model;

/**
 * Model pro správu budov.
 */
class BuildingsManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'buildings',
            COLUMN_ID = 'users_id',
            COLUMN_FARMER = 'farmer_building',
            COLUMN_BUILDER = 'builder_building',
            COLUMN_TRADER = 'trader_building',
            COLUMN_MINER = 'miner_building',
            COLUMN_BLACKSMITH = 'blacksmith_building',
            COLUMN_TOWER = 'tower',
            COLUMN_HOUSE = 'house';

    public function getBuildingsData($userID) {
        $data = (array) $this->database->query('SELECT gubernats.land, buildings.*, professions.* '
                . 'FROM gubernats '
                . 'LEFT JOIN buildings ON gubernats.gubernats_id = buildings.buildings_fk '
                . 'LEFT JOIN professions ON gubernats.gubernats_id = professions.professions_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;      
    }
    
    public function getSpecialBuildingsData($userID) {
        $data = (array) $this->database->query('SELECT gubernats.special_building, special_buildings.* '
                . 'FROM gubernats '
                . 'LEFT JOIN special_buildings ON gubernats.gubernats_fk = special_buildings.special_buildings_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;
    }
    
    public function destroySpecialBuilding($userID, $building) {
        $this->database->query('UPDATE special_buildings '
                . 'SET '.$building.' = ? '
                . 'WHERE special_buildings_fk = ?',$building, $userID);
    }
    
    public function buildSpecialBuilding($building, $userID) {
        $this->database->query('UPDATE gubernats '
                . 'SET special_building = ? '
                . 'WHERE gubernats_fk = ?',$building, $userID);
    }

    /**
     * Postavý zvolený počet budov
     * @param int $userID ID hráče
     * @param int $buildings počet stavěných budov
     * @param string $building typ budovy
     */
    public function addBuilding($userID, $buildings, $building) {
        $this->database->query('UPDATE buildings '
                . 'SET ' . $building . '_building = ' . $building . '_building + ' . $buildings
                . ' WHERE buildings_fk = ?',$userID);
    }

}
