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
    private $data;

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
    public function create($data) 
    {
        $this->data = $data;
        $race = $this->user->identity->data['race'];
        $units = array(
            array('soldier1', Race::getSoldier1($race)),
            array('soldier2', Race::getSoldier2($race)),
            array('mage', 'Mág')
        );
        $form = $this->formFactory->create();
        for($i = 0; $i < count($units); $i++) {
            $form->addText($units[$i][0], $units[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('add'.$units[$i][0], 'Naverbovat');
            $form->addCheckbox('check'.$units[$i][0]);
            $form->addSubmit('rem'.$units[$i][0], 'Propustit');
        }
        $form->onSuccess[] = [$this, 'armyFormSucceeded'];
        return $form;     
    }
    
    public function armyFormSucceeded (Form $form, \stdClass $values) {
        $presenter = $form->getPresenter();
        $unit = substr($form->isSubmitted()->name,3);
        $userID = $this->user->identity->getId();
        if(substr($form->isSubmitted()->name,0,3) == 'add') {
            $this->armyManager->addUnit($userID, $values[$unit], $unit);
            $presenter->flashMessage('Jednotky byly naverbovány.', 'notice');
        }
        else if((substr($form->isSubmitted()->name,0,3) == 'rem') && ($values['check'.$unit] == true)) {
            if($this->data[$unit] < $values[$unit]) {
                $presenter->flashMessage('Nemůžeš odebrat víc jednotek, než je momentálně naverbováno.', 'error');
            }
            else {
                $this->armyManager->addUnit($userID, -$values[$unit], $unit);
                $presenter->flashMessage('Jednotky byly propuštěny.', 'notice');
            }
        }
        $form->reset();
        $form->setDefaults([
            'soldier1' => 0,
            'soldier2' => 0,
            'mage' => 0
        ]);
    }
}
