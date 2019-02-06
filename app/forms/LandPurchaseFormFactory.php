<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\GubernatManager;
use Nette\SmartObject;
use Nette\Security\User;

class LandPurchaseFormFactory {

    use SmartObject;

    private $formFactory;
    private $gubernatManager;
    private $user;

    public function __construct(
            FormFactory $factory,
            GubernatManager $gubernatManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->gubernatManager = $gubernatManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro automatické nakupování pozemků.
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create() {
        $land = $this->gubernatManager->getLand($this->user->identity->getId());
        $form = $this->formFactory->create();
        $form->addText('land', 'Množství nakoupených pozemků')
                ->addRule(FORM::INTEGER)
                ->setDefaultValue(0)
                ->setRequired(TRUE)
                ->addRule(FORM::MIN,'Musí být kladné číslo',0);
        $form->addSubmit('buy', 'Koupit')
                ->onClick[] = [$this, 'landPurchaseFormBuy'];
        return $form;
    }

    public function landPurchaseFormBuy(\Nette\Forms\Controls\SubmitButton $button) {
        $form = $button->getForm();
        $values = $form->getValues();
        $userID = $this->user->identity->getId();
        $this->gubernatManager->updateLand($userID, $values['land']);
    }

}
