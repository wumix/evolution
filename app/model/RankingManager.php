<?php

namespace App\Model;

/**
 * Model pro správu uživatelů v systému.
 */
class RankingManager extends DatabaseManager {

    /** Konstanty pro práci s databází. */
    const
            TABLE_NAME = 'gubernats',
            COLUMN_ID = 'gubernats_fk',
            COLUMN_LAND = 'land',
            COLUMN_LAND_AUTO_PURCHASE = 'land_auto_purchase';
    
    public function getGubernatsRanking () {
        $gubernats = (array) $this->database->query('SELECT users.username, gubernats.land, gubernats.power, aliances.aliance_name '
                . 'FROM gubernats '
                . 'LEFT JOIN users ON gubernats.gubernats_fk = users.users_id '
                . 'LEFT JOIN aliances ON gubernats.aliances_fk = aliances.aliances_id '
                . 'ORDER BY gubernats.land DESC')->fetchAll();
        return $gubernats;
    }

}
