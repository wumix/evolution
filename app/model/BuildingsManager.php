<?php

namespace App\Model;

/**
 * Model pro správu budov.
 */
class BuildingsManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'buildings',
            COLUMN_ID = 'users_userID',
            COLUMN_FARMER = 'farmer',
            COLUMN_BUILDER = 'builder',
            COLUMN_TRADER = 'trader',
            COLUMN_MINER = 'miner',
            COLUMN_BLACKSMITH = 'blacksmith',
            COLUMN_TOWER = 'tower',
            COLUMN_HOUSE = 'house';

    /**
     * Vrátí počet jednotlivých budov
     * @param int $userID ID hráče
     * @return Array počet jednotlivých budov
     */
    public function getBuildings($userID) {

        $buildings = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch();
        $buildings->toArray();
        return $buildings;
    }

    /**
     * Postavý zvolený počet budov
     * @param int $userID ID hráče
     * @param int $buildings počet stavěných budov
     * @param string $building typ budovy
     */
    public function addBuilding($userID, $buildings, $building) {

        $this->database->query('UPDATE ' . self::TABLE_NAME . ' SET', [
            $building.'+=' => $buildings,
                ], 'WHERE users_userID = ?', $userID);
    }

}
