<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ArmyManager;
use App\Forms\ArmyFormFactory;
Use App\Classes\Number;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class ArmyPresenter extends BasePresenter {

    private $armyManager;
    private $armyFormFactory;
    private $data;

    public function __construct(
            ArmyManager $armyManager,
            ArmyFormFactory $armyFormFactory)
    {
        $this->armyManager = $armyManager;
        $this->armyFormFactory = $armyFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $this->data = $this->armyManager->getArmyData($userID);
        $this->template->data = Number::addSpacing($this->data);
    }

    protected function createComponentArmyForm() {
        return $this->armyFormFactory->create($this->data);
    }

}
