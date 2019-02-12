<?php

namespace App\Model;

/**
 * Model pro správu budov.
 */
class ArmyManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'army',
            COLUMN_ID = 'users_id',
            COLUMN_SOLDIER1= 'soldier1',
            COLUMN_SOLDIER2 = 'soldier2',
            COLUMN_MAGE = 'mage';

    public function getArmyData ($userID) {
        $data = (array) $this->database->query('SELECT gubernats.unemployed, resources.gold, resources.weapons, army.* '
                . 'FROM gubernats '
                . 'LEFT JOIN resources ON gubernats.gubernats_id = resources.resources_fk '
                . 'LEFT JOIN army ON gubernats.gubernats_id = army.army_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;
    }

    /**
     * Vrátí počet jednotlivých jednotek
     * @param int $userID ID hráče
     * @return Array počet jednotlivých jednotek
     */
    public function getArmy($userID) {

        $army = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch();
        $army->toArray();
        return $army;
    }

    /**
     * Naverbuje zvolený počet jednotek
     * @param int $userID ID hráče
     * @param int $units počet jednotek
     * @param string $unit typ jednotky
     */
    public function addUnit($userID, $units, $unit) {

        $this->database->query('UPDATE ' . self::TABLE_NAME . ' SET', [
            $unit.'+=' => $units,
                ], 'WHERE users_userID = ?', $userID);
    }

}
