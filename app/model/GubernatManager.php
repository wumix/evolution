<?php

namespace App\Model;

/**
 * Model pro správu uživatelů v systému.
 * @package App\Model
 */
class GubernatManager extends DatabaseManager
{
        /** Konstanty pro práci s databází. */
        const
                TABLE_NAME = 'gubernats',
                COLUMN_ID = 'users_userID',
                COLUMN_LAND_AUTO_PURCHASE = 'land_auto_purchase';
        

        /**
         * Vrátí procento automatického nákupu pozemků
         * @param int $userID ID hráče
         * @return Int $percent procento z příjmu, za které se nakupují pozemky
         */
        public function getLandAutoPurchase($userID) {

        $result = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch()->toArray();
        return $result['land_auto_purchase'];
    }
        /**
         * Aktualizuje procento automatického nákupu pozemků
         * @param int $userID ID hráče
         * @param int $newPercent nové procento
         */
        public function updateLandAutoPurchase($userID, $newPercent) {
        $this->database->query('UPDATE '.self::TABLE_NAME.' SET', [
            self::COLUMN_LAND_AUTO_PURCHASE => $newPercent            
        ],'WHERE users_userID = ?', $userID);
    }
    /**
         * Vrátí počet pozemků gubernátu
         * @param int $userID ID hráče
         * @return Int $land počet pozemků
         */
        public function getLand($userID) {

        $result = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_ID, $userID)->fetch()->toArray();
        return $result['land'];
    }

}
