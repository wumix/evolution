<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\BuildingsManager;
use App\Forms\BuildingsFormFactory;
use App\Forms\SpecialBuildingsFormFactory;
use App\Classes\Buildings;
use App\Classes\Number;
/**
 * Presenter pro vykreslování přehledu profesí
 */
class BuildingsPresenter extends BasePresenter {

    private $buildingsManager;
    private $buildingsFormFactory;
    private $specialBuildingsFormFactory;
    private $data;

    public function __construct(
            BuildingsManager $buildingsManager,
            BuildingsFormFactory $buildingsFormFactory,
            SpecialBuildingsFormFactory $specialBuildingsFormFactory)
    {
        $this->buildingsManager = $buildingsManager;
        $this->buildingsFormFactory = $buildingsFormFactory;
        $this->specialBuildingsFormFactory = $specialBuildingsFormFactory;
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
    
    public function renderSpecial() {
        $userID = $this->user->identity->getId();
        $this->data = $this->buildingsManager->getSpecialBuildingsData($userID);
        $buildings = array(
            array('building1', 'Radnice'),
            array('building2', 'Rozcestí'),
            array('building3', 'Hradby'),
            array('building4', 'Cvičiště'),
            array('building5', 'Magické oko'),
            array('building6', 'Pivnice'),
            array('building7', 'Chrám'),
            array('building8', 'Palác času')
        );
    }

    protected function createComponentBuildingsForm() {
        return $this->buildingsFormFactory->create($this->data);
    }
    
    protected function createComponentSpecialBuildingsForm() {
        return $this->specialBuildingsFormFactory->create($this->data);
    }

}
