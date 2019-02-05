<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ResourcesManager;
use App\Model\GubernatManager;

/**
 * Presenter pro vykreslování hlavního přehledu gubernátu
 */
class GubernatPresenter extends BasePresenter {

    private $resourcesManager;
    private $gubernatManager;

    /**
     * @param ResourcesManager $resourcesManager automaticky injektovaný model pro správu surovin
     */
    public function __construct(ResourcesManager $resourcesManager, GubernatManager $gubernatManager) {
        $this->resourcesManager = $resourcesManager;
        $this->gubernatManager = $gubernatManager;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $this->template->username = $this->user->identity->username;
        $this->template->resources = $this->resourcesManager->getResources($userID);
        $this->template->land = $this->gubernatManager->getLand($userID);
    }

}
