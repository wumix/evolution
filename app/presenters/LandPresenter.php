<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Forms\AutoLandPurchaseFormFactory;
use App\Forms\LandPurchaseFormFactory;
use App\Model\GubernatManager;

/**
 * Presenter pro vykreslování správy pozemků
 */
class LandPresenter extends BasePresenter {

    private $autoLandPurchaseFormFactory;
    private $landPurchaseFormFactory;
    private $gubernatManager;

    /**
     * @param AutoLandPurchaseFormFactory $autoLandPurchaseFormFactory
     */
    public function __construct(AutoLandPurchaseFormFactory $autoLandPurchaseFormFactory, LandPurchaseFormFactory $landPurchaseFormFactory, GubernatManager $gubernatManager) {
        $this->autoLandPurchaseFormFactory = $autoLandPurchaseFormFactory;
        $this->landPurchaseFormFactory = $landPurchaseFormFactory;
        $this->gubernatManager = $gubernatManager;
    }

    public function renderDefault() {
        
    }

    protected function createComponentAutoLandPurchaseForm() {
        return $this->autoLandPurchaseFormFactory->create(function (Form $form, ArrayHash $values) {
                    $this->flashMessage('Změněno');
                });
    }

    protected function createComponentLandPurchaseForm() {
        return $this->landPurchaseFormFactory->create(function (Form $form, ArrayHash $values) {
                    $userID = $this->user->identity->getId();
                    $this->gubernatManager->updateLand($userID, $land);
                    $this->flashMessage('Pozemky byly nakoupeny');
                });
    }

}
