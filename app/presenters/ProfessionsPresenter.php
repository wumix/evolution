<?php

namespace App\Presenters;

use App\Presenters\BasePresenter;
use App\Model\ProfessionsManager;
use App\Forms\ProfessionsFormFactory;
use App\Classes\Number;

/**
 * Presenter pro vykreslování přehledu profesí
 */
class ProfessionsPresenter extends BasePresenter {

    private $professionsManager;
    private $professionsFormFactory;
    private $data;

    public function __construct(
            ProfessionsManager $professionsManager,
            ProfessionsFormFactory $professionsFormFactory)
    {
        $this->professionsManager = $professionsManager;
        $this->professionsFormFactory = $professionsFormFactory;
    }

    /** Předá údaje o gubernátu do šablony hlavního přehledu gubernátu */
    public function renderDefault() {
        $userID = $this->user->identity->getId();
        $this->data = $this->professionsManager->getProfessionsData($userID);
        $this->template->data = Number::addSpacing($this->data);
    }

    protected function createComponentProfessionsForm() {
        return $this->professionsFormFactory->create($this->data);
    }
    
    
}
