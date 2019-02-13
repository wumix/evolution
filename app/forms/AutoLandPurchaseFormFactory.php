<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\GubernatManager;
use Nette\SmartObject;
use Nette\Security\User;

class AutoLandPurchaseFormFactory {

    use SmartObject;

    private $formFactory;
    private $gubernatManager;
    private $user;

    /**
     * @param FormFactory $factory automaticky injektovaná továrna na formuláře
     * @param GubernatManager $gubernatManager automaticky injektovaný model pro správu gubernátu
     */
    public function __construct (FormFactory $factory, GubernatManager $gubernatManager, User $user) {
        $this->formFactory = $factory;
        $this->gubernatManager = $gubernatManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro automatické nakupování pozemků.
     * @param Int $autoLandPurchase procento nakupování pozemků
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create ($autoLandPurchase) {
        $form = $this->formFactory->create();
        $form->addSelect('percent', 'Procent z příjmu:', [
            0 => '0',
            10 => '10',
            20 => '20',
            30 => '30',
            40 => '40',
            50 => '50',
            60 => '60',
            70 => '70',
            80 => '80',
            90 => '90',
            100 => '100',
        ])
                ->setDefaultValue($autoLandPurchase);
        $form->addSubmit('change', 'Změnit')
            ->onClick[] = [$this, 'landAutoPurchaseFormChange'];
        return $form;
    }
    
    public function landAutoPurchaseFormChange (\Nette\Forms\Controls\SubmitButton $button) {
        $form = $button->getForm();
        $values = $form->getValues();
        $userID = $this->user->identity->getId();
        $this->gubernatManager->updateLandAutoPurchase($userID, $values['percent']);
    }

}
