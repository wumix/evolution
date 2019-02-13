<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\BuildingsManager;
use App\Forms\BuildingsFormFactory;
use App\Classes\Buildings;
use App\Classes\Number;
/**
 * Presenter pro vykreslování přehledu profesí
 */
class BuildingsPresenter extends BasePresenter {

    private $buildingsManager;
    private $buildingsFormFactory;
    private $data;

    public function __construct(
            BuildingsManager $buildingsManager,
            BuildingsFormFactory $buildingsFormFactory)
    {
        $this->buildingsManager = $buildingsManager;
        $this->buildingsFormFactory = $buildingsFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $buildings = array(
            'farmer',
            'trader',
            'alchemist',
            'builder',
            'miner',
            'blacksmith',
        );
        $this->data = $this->buildingsManager->getBuildingsData($userID);
        foreach ($buildings as $building) {
            $this->data[$building.'_bonus'] = Buildings::getBonus($this->data[$building.'_building'], $this->data[$building])*100;
        }
        $this->template->data = Number::addSpacing($this->data);
    }

    protected function createComponentBuildingsForm() {
        return $this->buildingsFormFactory->create($this->data);
    }

}
