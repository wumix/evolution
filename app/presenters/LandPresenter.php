<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Forms\AutoLandPurchaseFormFactory;
use App\Forms\LandPurchaseFormFactory;

/**
 * Presenter pro vykreslování správy pozemků
 * @package App\CoreModule\Presenters
 */
class LandPresenter extends BasePresenter
{

    /** @var autoLandPurchaseFormFactory */
    private $autoLandPurchaseFormFactory;
    private $landPurchaseFormFactory;


    /**
     * LandPresenter constructor.
     * @param AutoLandPurchaseFormFactory $autoLandPurchaseFormFactory
     */
    public function __construct(AutoLandPurchaseFormFactory $autoLandPurchaseFormFactory, LandPurchaseFormFactory $landPurchaseFormFactory)
    {
        $this->autoLandPurchaseFormFactory = $autoLandPurchaseFormFactory;
        $this->landPurchaseFormFactory = $landPurchaseFormFactory;
    }
    
    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault()
    {
            
    }
    
    protected function createComponentAutoLandPurchaseForm()
    {
        return $this->autoLandPurchaseFormFactory->create(function (Form $form, ArrayHash $values) {
            $this->flashMessage('Změněno');
        });
    }
    
    protected function createComponentLandPurchaseForm()
    {
        return $this->landPurchaseFormFactory->create(function (Form $form, ArrayHash $values) {
            $this->flashMessage('Pozemky byly nakoupeny');
        });
    }


}