<?php

namespace App\Model;

/**
 * Model pro správu uživatelů v systému.
 */
class ResourcesManager extends DatabaseManager
{
        /** Konstanty pro práci s databází. */
        const
                TABLE_NAME = 'resources',
                COLUMN_ID = 'users_userID',
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

        $resources = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch();
        $userAttributes = $resources->toArray();
        return $userAttributes;
    }
        /**
         * Aktualizuje množství surovin po skončení tahu
         * @param int array $resourcesAdd získané suroviny
         * @param int array $resourcesRemove ztracené suroviny
         */
        public function updateResourcesNextTurn($userID, $resourcesAdd, $resourcesRemove) {

        $this->database->query('UPDATE '.self::TABLE_NAME.' SET', [
            self::COLUMN_GOLD => self::COLUMN_GOLD + ($resourcesAdd(self::COLUMN_GOLD)-$resourcesRemove(self::COLUMN_GOLD)),
            self::COLUMN_FOOD => self::COLUMN_FOOD + ($resourcesAdd(self::COLUMN_FOOD)-$resourcesRemove(self::COLUMN_FOOD)),
            self::COLUMN_WEAPONS => self::COLUMN_WEAPONS + ($resourcesAdd(self::COLUMN_WEAPONS)-$resourcesRemove(self::COLUMN_WEAPONS)),
            self::COLUMN_STONES => self::COLUMN_STONES + ($resourcesAdd(self::COLUMN_STONES)-$resourcesRemove(self::COLUMN_STONES))
        ],'WHERE users_userID = ?', $userID);
    }

}
