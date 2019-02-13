<?php

namespace App\Forms;

use App\Model\ProfessionsManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Security\User;

class ProfessionsFormFactory {

    use SmartObject;

    private $formFactory;
    private $professionsManager;
    private $user;
    private $data;

    public function __construct(
            FormFactory $factory,
            ProfessionsManager $professionsManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->professionsManager = $professionsManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro přidávání a odebírání lidí z profesí
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create($data) 
    {
        $this->data = $data;
        $professions = array(
            array('farmer', 'Farmáři'),
            array('trader', 'Obchodníci'),
            array('alchemist', 'Alchymisti'),
            array('builder', 'Zedníci'),
            array('miner', 'Kameníci'),
            array('blacksmith', 'Kováři')
        );
        $form = $this->formFactory->create();
        for ($i = 0; $i < count($professions); $i++) {
            $form->addText($professions[$i][0], $professions[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('add'.$professions[$i][0], 'Zaměstnat');
            $form->addCheckbox('check'.$professions[$i][0]);
            $form->addSubmit('rem'.$professions[$i][0], 'Propustit');
        }
        $form->onSuccess[] = [$this, 'professionsFormSucceeded'];
        return $form;  
    }
    
    public function professionsFormSucceeded (Form $form, \stdClass $values) {
        $presenter = $form->getPresenter();
        $profession = substr($form->isSubmitted()->name,3);
        $userID = $this->user->identity->getId();
        if(substr($form->isSubmitted()->name,0,3) == 'add') {
            $this->professionsManager->addProfession($userID, $values[$profession], $profession);
            $presenter->flashMessage('Lidé byli zařazeni do zaměstnání.', 'notice');
        }
        else if((substr($form->isSubmitted()->name,0,3) == 'rem') && ($values['check'.$profession] == true)) {
            if($this->data[$profession] < $values[$profession]) {
                $presenter->flashMessage('Nemůžeš odebrat víc lidí, než je momentálně zaměstnáno.', 'error');
            }
            else {
                $this->professionsManager->addProfession($userID, -$values[$profession], $profession);
                $presenter->flashMessage('Lidé byli propuštěni ze zaměstnání.', 'notice');
            }
        }
        $form->reset();
        $form->setDefaults([
            'farmer' => 0,
            'trader' => 0,
            'alchemist' => 0,
            'builder' => 0,
            'miner' => 0,
            'blacksmith' => 0
        ]); 
    }
}

