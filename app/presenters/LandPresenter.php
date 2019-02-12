<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Forms\AutoLandPurchaseFormFactory;
use App\Forms\LandPurchaseFormFactory;
use App\Model\GubernatManager;
use App\Classes\Number;
use App\Classes\Land;

/**
 * Presenter pro vykreslování správy pozemků
 */
class LandPresenter extends BasePresenter {

    private $autoLandPurchaseFormFactory;
    private $landPurchaseFormFactory;
    private $gubernatManager;
    private $data;

    public function __construct(AutoLandPurchaseFormFactory $autoLandPurchaseFormFactory, LandPurchaseFormFactory $landPurchaseFormFactory, GubernatManager $gubernatManager) {
        $this->autoLandPurchaseFormFactory = $autoLandPurchaseFormFactory;
        $this->landPurchaseFormFactory = $landPurchaseFormFactory;
        $this->gubernatManager = $gubernatManager;
    }

    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $this->data = $this->gubernatManager->getLandData($userID);
        $this->data['landPrice'] = Land::getLandPrice($this->data['land']);
        $this->data['avalibleLand'] = Land::getAvalibleLand($this->data['gold'], $this->data['land']);
        $this->template->data = Number::addSpacing($this->data);
    }

    protected function createComponentAutoLandPurchaseForm() {
        return $this->autoLandPurchaseFormFactory->create($this->data['land_auto_purchase']);
    }

    protected function createComponentLandPurchaseForm() {
        return $this->landPurchaseFormFactory->create();
    }

}
