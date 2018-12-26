<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ResourcesManager;

/**
 * Presenter pro vykreslování hlavního přehledu gubernátu
 * @package App\CoreModule\Presenters
 */
class GubernatPresenter extends BasePresenter
{

    private $resourcesManager;

    /**
     * Konstruktor s injektovaným modelem pro správu surovin
     * @param ResourcesManager $resourcesManager automaticky injektovaný model pro správu surovin
     */
    public function __construct(ResourcesManager $resourcesManager)
    {
        $this->resourcesManager = $resourcesManager;
    }
    
    
    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault()
    {
        $this->template->username = $this->user->identity->username;
        $this->template->resources = $this->resourcesManager->getResources($this->user->identity->getId());
    }


}