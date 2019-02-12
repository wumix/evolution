<?php

namespace App\Model;

/**
 * Model pro správu profesí.
 */
class ProfessionsManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'professions',
            COLUMN_ID = 'users_id',
            COLUMN_FARMER = 'farmer',
            COLUMN_BUILDER = 'builder',
            COLUMN_TRADER = 'trader',
            COLUMN_MINER = 'miner',
            COLUMN_BLACKSMITH = 'blacksmith';

    public function getProfessionsData($userID) {
        $data = (array) $this->database->query('SELECT gubernats.unemployed, professions.*, professions_gains.* '
                . 'FROM gubernats '
                . 'LEFT JOIN professions ON gubernats.gubernats_id = professions.professions_fk '
                . 'LEFT JOIN professions_gains ON gubernats.gubernats_id = professions_gains_fk '
                . 'WHERE gubernats.gubernats_fk = ?',$userID)->fetch();
        return $data;         
    }
    /**
     * Vrátí počet obyvatel v jednotlivých profesích
     * @param int $userID ID hráče
     * @return Array počet obyvatel v jednotlivých profesích
     */
    public function getProfessions($userID) {

        return $this->database->query('SELECT *'
                . ' FROM ' . self::TABLE_NAME
                . ' WHERE ' . self::COLUMN_ID . ' = ?', $userID)->Fetch(); 
    }

    /**
     * Přidá zvolený počet obyvatel do profese
     * @param int $userID ID hráče
     * @param int $citizens počet přidaných obyvatel
     * @param string $profession profese
     */
    public function addProfession($userID, $workers, $profession) {
        $this->database->query('UPDATE professions '
                . 'SET ' . $profession . ' = ' . $profession . ' + ' . $workers
                . ' WHERE professions_fk = ?',$userID);
    }

}
