<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\GubernatManager;
use App\Classes\Number;

/**
 * Presenter pro vykreslování hlavního přehledu gubernátu
 */
class GubernatPresenter extends BasePresenter {
    
    private $gubernatManager;

    public function __construct(GubernatManager $gubernatManager) {
        $this->gubernatManager = $gubernatManager;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();   
        $this->template->data = Number::addSpacing($this->gubernatManager->getGubernatData($userID));
        $this->template->username = $this->user->identity->username;

        
        
    }

}
