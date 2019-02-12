<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Security\User;
use App\Model\ArmyManager;
use App\Classes\Race;


class ArmyFormFactory {

    use SmartObject;

    private $formFactory;
    private $armyManager;
    private $user;

    public function __construct(
            FormFactory $factory,
            ArmyManager $armyManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->armyManager = $armyManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro stavění a bourání budov
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create() 
    {
        $race = $this->user->identity->data['race'];
        $form = $this->formFactory->create();
            $form->addText('soldier1', Race::getSoldier1($race))
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('addsoldier1', 'Naverbovat')
                    ->onClick[] = [$this, 'soldierFormAdd'];
            $form->addCheckbox('checksoldier1');
            $form->addSubmit('remsoldier1', 'Propustit')
                    ->onClick[] = [$this, 'soldierFormRemove'];
            $form->addText('soldier2', Race::getSoldier2($race))
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('addsoldier2', 'Naverbovat')
                    ->onClick[] = [$this, 'soldierFormAdd'];
            $form->addCheckbox('checksoldier2');
            $form->addSubmit('remsoldier2', 'Propustit')
                    ->onClick[] = [$this, 'soldierFormRemove'];   
        return $form;
    }

    public function soldierFormAdd(\Nette\Forms\Controls\SubmitButton $button)
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $soldier = substr($button->name,3);
        $userID = $this->user->identity->getId();
        $this->armyManager->addUnit($userID, $values[$soldier], $soldier);
        $form->reset();
        $form->setDefaults([
            'soldier1' => 0,
            'soldier2' => 0
        ]);
    }

    public function soldierFormRemove(\Nette\Forms\Controls\SubmitButton $button) 
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $soldier = substr($button->name,3);
        if($values['check'.$soldier] == true) {
            $userID = $this->user->identity->getId();
            $this->armyManager->addUnit($userID, -$values[$soldier], $soldier);
        }
        $form->reset();
        $form->setDefaults([
            'soldier1' => 0,
            'soldier2' => 0
        ]);
    }

}
