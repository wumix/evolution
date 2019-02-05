<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\GubernatManager;
use App\Model\ProffesionsManager;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Security\User;

class ProffesionsFormFactory {

    use SmartObject;

    private $formFactory;
    private $gubernatManager;
    private $proffesionsManager;
    private $user;

    /**
     * @param FormFactory $factory automaticky injektovaná továrna na formuláře
     * @param GubernatManager $gubernatManager automaticky injektovaný model pro správu gubernátu
     */
    public function __construct(
            FormFactory $factory,
            GubernatManager $gubernatManager,
            ProffesionsManager $proffesionsManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->gubernatManager = $gubernatManager;
        $this->proffesionsManager = $proffesionsManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro přidávání a odebírání lidí z profesí
     * @param callable $onSuccess specifická funkce, která se vykoná po úspěšném odeslání formuláře
     * @param string $proffesion profese (anglicky)
     * @param string $label popisek profese (česky)
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create() 
    {
        $proffesions = array(
            array('farmer', 'Farmáři'),
            array('builder', 'Zedníci'),
            array('trader', 'Obchodníci'),
            array('miner', 'Kameníci'),
            array('blacksmith', 'Kováři')
        );
        $form = $this->formFactory->create();
        for ($i = 0; $i < 5; $i++) {
            $form->addText($proffesions[$i][0], $proffesions[$i][1])
                    ->addRule(FORM::INTEGER)
                    ->setDefaultValue(0)
                    ->setRequired(true);
            $form->addSubmit('add'.$proffesions[$i][0], 'Přidat')
                    ->onClick[] = [$this, 'proffesionsFormAdd'];
            $form->addSubmit('rem'.$proffesions[$i][0], 'Odebrat')
                    ->onClick[] = [$this, 'proffesionsFormRemove'];
        }

        return $form;
    }

    public function proffesionsFormAdd(\Nette\Forms\Controls\SubmitButton $button)
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $proffesion = substr($button->name,3);
        $userID = $this->user->identity->getId();
        $this->proffesionsManager->addProffesion($userID, $values[$proffesion], $proffesion);
    }

    public function proffesionsFormRemove(\Nette\Forms\Controls\SubmitButton $button) 
    {
        $form = $button->getForm();
        $values = $form->getValues();
        $proffesion = substr($button->name,3);
        $userID = $this->user->identity->getId();
        $this->proffesionsManager->addProffesion($userID, -$values[$proffesion], $proffesion);
    }

}
