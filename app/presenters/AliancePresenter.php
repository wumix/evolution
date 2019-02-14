<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Forms\AlianceCreateFormFactory;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class AliancePresenter extends BasePresenter {

    private $alianceCreateFormFactory;

    public function __construct(
            AlianceCreateFormFactory $alianceFormFactory)
    {
        $this->alianceCreateFormFactory = $alianceFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderCreate() {
        
    }
    
    public function renderShow() {
        
    }
    
    public function createComponentAlianceCreateForm() {
        return $this->alianceCreateFormFactory->create();
    }
}