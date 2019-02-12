<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\BuildingsManager;
use Nette\SmartObject;
use Nette\Security\User;

class BuildingsFormFactory {

    use SmartObject;

    private $formFactory;
    private $buildingsManager;
    private $user;

    public function __construct(
            FormFactory $factory,
            BuildingsManager $buildingsManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->buildingsManager = $buildingsManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro stavění a bourání budov
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create() 
    {
        $buildings = array(
            array('farmer', 'Farmáři'),
            array('trader', 'Obchodníci'),
            array('alchemist', 'Alchymisti'),
            array('builder', 'Zedníci'),
            array('miner', 'Kameníci'),
            array('blacksmith', 'Kováři'),
            array('house', 'Obytené domy'),
            array('tower', 'Věže')
        );
        $form = $this->formFactory->create();
        for ($i = 0; $i < 8; $i++) {
            $form->addText($buildings[$i][0], $buildings[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('add'.$buildings[$i][0], 'Postavit')
                    ->onClick[] = [$this, 'buildingsFormAdd'];
            $form->addCheckbox('check'.$buildings[$i][0]);
            $form->addSubmit('rem'.$buildings[$i][0], 'Zbourat')
                    ->onClick[] = [$this, 'buildingsFormRemove'];
        }

        return $form;
    }

    public function buildingsFormAdd(\Nette\Forms\Controls\SubmitButton $button)
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $building = substr($button->name,3);
        $userID = $this->user->identity->getId();
        $this->buildingsManager->addBuilding($userID, $values[$building], $building);
        $form->reset();
        $form->setDefaults([
            'farmer' => 0,
            'trader' => 0,
            'alchemist' => 0,
            'builder' => 0,   
            'miner' => 0,
            'blacksmith' => 0,
            'house' => 0,
            'tower' => 0
        ]);
    }

    public function buildingsFormRemove(\Nette\Forms\Controls\SubmitButton $button) 
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $building = substr($button->name,3);
        if($values['check'.$building] == true) {
            $userID = $this->user->identity->getId();
            $this->buildingsManager->addBuilding($userID, -$values[$building], $building);
        }
        $form->reset();
        $form->setDefaults([
            'farmer' => 0,
            'trader' => 0,
            'alchemist' => 0,
            'builder' => 0,   
            'miner' => 0,
            'blacksmith' => 0,
            'house' => 0,
            'tower' => 0
        ]);
    }

}
