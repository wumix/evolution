<?php

namespace App\Forms;
use Nette\Application\UI\Form;
use App\Model\ProfessionsManager;
use Nette\SmartObject;
use Nette\Security\User;

class ProfessionsFormFactory {

    use SmartObject;

    private $formFactory;
    private $professionsManager;
    private $user;

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
    public function create() 
    {
        $professions = array(
            array('farmer', 'Farmáři'),
            array('trader', 'Obchodníci'),
            array('alchemist', 'Alchymisti'),
            array('builder', 'Zedníci'),
            array('miner', 'Kameníci'),
            array('blacksmith', 'Kováři')
        );
        $form = $this->formFactory->create();
        for ($i = 0; $i < 6; $i++) {
            $form->addText($professions[$i][0], $professions[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true)
                    ->addRule(FORM::MIN,'Musí být kladné číslo',0);
            $form->addSubmit('add'.$professions[$i][0], 'Zaměstnat')
                    ->onClick[] = [$this, 'professionsFormAdd'];
            $form->addCheckbox('check'.$professions[$i][0]);
            $form->addSubmit('rem'.$professions[$i][0], 'Propustit')
                    ->onClick[] = [$this, 'professionsFormRemove'];
        }

        return $form;
    }

    public function professionsFormAdd(\Nette\Forms\Controls\SubmitButton $button)
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $profession = substr($button->name,3);
        $userID = $this->user->identity->getId();
        $this->professionsManager->addProfession($userID, $values[$profession], $profession);
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

    public function professionsFormRemove(\Nette\Forms\Controls\SubmitButton $button) 
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $profession = substr($button->name,3);
        if($values['check'.$profession] == true) {
            $userID = $this->user->identity->getId();
            $this->professionsManager->addProfession($userID, -$values[$profession], $profession);
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
