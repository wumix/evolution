<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ResourcesManager;
use App\Model\GubernatManager;
use App\Model\ProffesionsManager;
use App\Forms\ProffesionsFormFactory;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class ProffesionsPresenter extends BasePresenter {

    private $resourcesManager;
    private $gubernatManager;
    private $proffesionsManager;
    private $proffesionsFormFactory;

    /**
     * @param ResourcesManager $resourcesManager automaticky injektovaný model pro správu surovin
     * @param GubernatManager $gubernatManager automaticky injektovaný model pro správu gubernátu
     */
    public function __construct(ResourcesManager $resourcesManager, GubernatManager $gubernatManager, ProffesionsManager $proffesionsManager, ProffesionsFormFactory $proffesionsFormFactory) {
        $this->resourcesManager = $resourcesManager;
        $this->gubernatManager = $gubernatManager;
        $this->proffesionsManager = $proffesionsManager;
        $this->proffesionsFormFactory = $proffesionsFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $this->template->username = $this->user->identity->username;
        $this->template->proffesions = $this->proffesionsManager->getProffesions($this->user->identity->getId());
    }

    protected function createComponentProffesionsForm() {
        return $this->proffesionsFormFactory->create();
    }

}
