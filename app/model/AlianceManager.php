<?php

namespace App\Model;

/**
 * Model pro správu uživatelů v systému.
 */
class AlianceManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'aliances',
            COLUMN_ID = 'gubernats_fk';
    
    public function addAliance ($name, $userID) {
        $this->database->query('INSERT INTO aliances (imperator_fk, aliance_name) '
                . 'VALUES (?, ?)',$userID, $name);
        $id = $this->database->getInsertId();
        $this->addMember($id, $userID);
        return $id;
    }
    
    public function addInvitation ($alianceID, $userID) {
        $this->database->query('INSERT INTO aliances_invitations (aliances_fk, gubernats_fk) '
                . 'VALUES (?, ?)', $alianceID, $userID);
    }
    
    public function addMember ($alianceID, $userID) {
        $this->database->query('UPDATE gubernats '
                . 'SET aliances_fk = ? '
                . 'WHERE gubernats_fk = ?', $alianceID, $userID);
    }

}
