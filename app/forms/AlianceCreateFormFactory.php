<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Security\User;
use App\Model\AlianceManager;


class AlianceCreateFormFactory {

    use SmartObject;

    private $formFactory;
    private $alianceManager;
    private $user;

    public function __construct(
            FormFactory $factory,
            AlianceManager $alianceManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->alianceManager = $alianceManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro stavění a bourání budov
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create() {
        $form = $this->formFactory->create();
            $form->addText('name', 'Jméno aliance')
                    ->setRequired(true);
            $form->addSubmit('create', 'Založit');
        $form->onSuccess[] = [$this, 'alianceFormSucceeded'];
        return $form;     
    }
    
    public function alianceFormSucceeded (Form $form, \stdClass $values) {
        $presenter = $form->getPresenter();
        $userID = $this->user->identity->getId();
        $alianceID = $this->alianceManager->addAliance($values['name'], $userID);
        $this->user->identity->aliances_fk = $alianceID;
        $presenter->redirect('Aliance:show');       
    }
}
