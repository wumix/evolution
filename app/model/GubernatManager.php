<?php

namespace App\Model;

/**
 * Model pro správu uživatelů v systému.
 */
class GubernatManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'gubernats',
            COLUMN_ID = 'gubernats_fk',
            COLUMN_LAND = 'land',
            COLUMN_LAND_AUTO_PURCHASE = 'land_auto_purchase';
    
    public function getGubernatData($userID) {
        $data = (array) $this->database->query('SELECT gubernats.*, resources.*, professions_gains.* '
                . 'FROM gubernats '
                . 'LEFT JOIN resources ON gubernats.gubernats_id = resources.resources_fk '
                . 'LEFT JOIN professions_gains ON gubernats.gubernats_id = professions_gains.professions_gains_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;
    }
    
    public function getLandData($userID) {
        $data = (array) $this->database->query('SELECT gubernats.land, gubernats.land_auto_purchase, resources.gold '
                . 'FROM gubernats '
                . 'LEFT JOIN resources ON gubernats.gubernats_id = resources.resources_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;
    }

    /**
     * Aktualizuje procento automatického nákupu pozemků
     * @param int $userID ID hráče
     * @param int $newPercent nové procento
     */
    public function updateLandAutoPurchase($userID, $newPercent) {
        $this->database->query('UPDATE ' . self::TABLE_NAME
                . ' SET ' . self::COLUMN_LAND_AUTO_PURCHASE . ' = ? ' 
                . ' WHERE ' . self::COLUMN_ID . ' = ?', $newPercent, $userID);
    }
    
    /**
     * Změní počet pozemků gubernátu
     * @param int $userID ID hráče
     * @param int $land změna v pozem
     */
    public function updateLand($userID, $land) {
        $this->database->query('UPDATE ' . self::TABLE_NAME
                . ' SET ' . self::COLUMN_LAND . ' = ' . self::COLUMN_LAND . ' + ' . $land
                . ' WHERE ' . self::COLUMN_ID . ' = ?', $userID);
    }

}
