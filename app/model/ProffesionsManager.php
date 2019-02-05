<?php

namespace App\Model;

/**
 * Model pro správu profesí.
 */
class ProffesionsManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'proffesions',
            COLUMN_ID = 'users_userID',
            COLUMN_FARMER = 'farmer',
            COLUMN_BUILDER = 'builder',
            COLUMN_TRADER = 'trader',
            COLUMN_MINER = 'miner',
            COLUMN_BLACKSMITH = 'blacksmith',
            COLUMN_MAGE = 'mage',
            COLUMN_SOLDIER1 = 'soldier1',
            COLUMN_SOLDIER2 = 'soldier2';

    /**
     * Vrátí počet obyvatel v jednotlivých profesích
     * @param int $userID ID hráče
     * @return Array počet obyvatel v jednotlivých profesích
     */
    public function getProffesions($userID) {

        $proffesions = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch();
        $proffesions->toArray();
        return $proffesions;
    }

    /**
     * Přidá zvolený počet obyvatel do profese
     * @param int $userID ID hráče
     * @param int $citizens počet přidaných obyvatel
     * @param string $proffesion profese
     */
    public function addProffesion($userID, $citizens, $proffesion) {

        $this->database->query('UPDATE ' . self::TABLE_NAME . ' SET', [
            $proffesion.'+=' => $citizens,
                ], 'WHERE users_userID = ?', $userID);
    }

}
