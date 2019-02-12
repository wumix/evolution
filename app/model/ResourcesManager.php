<?php

namespace App\Model;

/**
 * Model pro správu surovin.
 */
class ResourcesManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'resources',
            COLUMN_ID = 'users_id',
            COLUMN_GOLD = 'gold',
            COLUMN_FOOD = 'food',
            COLUMN_WEAPONS = 'weapons',
            COLUMN_STONES = 'stones';

    /**
     * Vrátí počet surovin hráče
     * @param int $userID ID hráče
     * @return Array počet jednotlivých surovin v asociativním poli
     */
    public function getResources($userID) {
        $result = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch()->toArray();
        return $result;
    }

}
