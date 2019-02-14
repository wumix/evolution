<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\RankingManager;
use App\Classes\Number;

/**
 * Presenter pro vykreslování hlavního přehledu gubernátu
 */
class RankingPresenter extends BasePresenter {
    
    private $rankingManager;

    public function __construct(RankingManager $rankingManager) {
        $this->rankingManager = $rankingManager;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() { 
        $this->template->gubernats = Number::addSpacing($this->rankingManager->getGubernatsRanking());  
    }
}
