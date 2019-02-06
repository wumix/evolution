<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ResourcesManager;
use App\Model\GubernatManager;
use App\Model\ProffesionsManager;
use App\Model\BuildingsManager;
use App\Forms\BuildingsFormFactory;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class BuildingsPresenter extends BasePresenter {

    private $resourcesManager;
    private $gubernatManager;
    private $proffesionsManager;
    private $buildingsManager;
    private $buildingsFormFactory;

    public function __construct(
            ResourcesManager $resourcesManager,
            GubernatManager $gubernatManager,
            ProffesionsManager $proffesionsManager,
            BuildingsManager $buildingsManager,
            BuildingsFormFactory $buildingsFormFactory)
    {
        $this->resourcesManager = $resourcesManager;
        $this->gubernatManager = $gubernatManager;
        $this->proffesionsManager = $proffesionsManager;
        $this->buildingsManager = $buildingsManager;
        $this->buildingsFormFactory = $buildingsFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $this->template->username = $this->user->identity->username;
        $this->template->proffesions = $this->proffesionsManager->getProffesions($userID);
        $this->template->buildings = $this->buildingsManager->getBuildings($userID);
    }

    protected function createComponentBuildingsForm() {
        return $this->buildingsFormFactory->create();
    }

}
