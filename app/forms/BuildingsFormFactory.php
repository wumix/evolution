<?php

namespace App\Forms;

use App\Model\BuildingsManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Security\User;

class BuildingsFormFactory {

    use SmartObject;

    private $formFactory;
    private $buildingsManager;
    private $user;
    private $data;

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
    public function create($data) 
    {
        $this->data = $data;
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
        for ($i = 0; $i < count($buildings); $i++) {
            $form->addText($buildings[$i][0], $buildings[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('add'.$buildings[$i][0], 'Postavit');
            $form->addCheckbox('check'.$buildings[$i][0]);
            $form->addSubmit('rem'.$buildings[$i][0], 'Zbourat');
        }
        $form->onSuccess[] = [$this, 'buildingsFormSucceeded'];
        return $form;
    }

    public function buildingsFormSucceeded(Form $form, \stdClass $values) {
        $presenter = $form->getPresenter();
        $building = substr($form->isSubmitted()->name,3);
        $userID = $this->user->identity->getId();
        if(substr($form->isSubmitted()->name,0,3) == 'add') {
            $this->buildingsManager->addBuilding($userID, $values[$building], $building);
            $presenter->flashMessage('Budovy byly postaveny.', 'notice');
        }
        else if((substr($form->isSubmitted()->name,0,3) == 'rem') && ($values['check'.$building] == true)) {
            if($this->data[$building] < $values[$building]) {
                $presenter->flashMessage('Nemůžeš zbourat víc budov, než je momentálně postaveno.', 'error');
            }
            else {
                $this->buildingsManager->addBuilding($userID, -$values[$building], $building);
                $presenter->flashMessage('Budovy byly zbourány.', 'notice');
            }
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
