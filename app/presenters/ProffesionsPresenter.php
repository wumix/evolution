<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ResourcesManager;
use App\Model\GubernatManager;
use App\Model\ProffesionsManager;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class ProffesionsPresenter extends BasePresenter
{

    private $resourcesManager;
    private $gubernatManager;
    private $proffesionsManager;

    /**
     * @param ResourcesManager $resourcesManager automaticky injektovaný model pro správu surovin
     * @param GubernatManager $gubernatManager automaticky injektovaný model pro správu gubernátu
     */
    public function __construct(ResourcesManager $resourcesManager, GubernatManager $gubernatManager, ProffesionsManager $proffesionsManager)
    {
        $this->resourcesManager = $resourcesManager;
        $this->gubernatManager = $gubernatManager;
        $this->proffesionsManager = $proffesionsManager;
    }
    
    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault()
    {
        $this->template->username = $this->user->identity->username;
        $this->template->resources = $this->resourcesManager->getResources($this->user->identity->getId());
    }


}