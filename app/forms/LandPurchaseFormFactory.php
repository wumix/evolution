<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use App\Model\GubernatManager;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use Nette\Security\User;


class LandPurchaseFormFactory {
    
    use SmartObject;
    
    /** @var FormFactory Továrna na formuláře. */
    private $formFactory;
    
    /** @var GubernatManager Model pro správu gubernátu. */
    private $gubernatManager;
    
    /** @var User přihlášený uživatel. */
    private $user;


    /**
     * Konstruktor s injektovanou továrnou na formuláře a modelem pro správu gubernátu
     * @param FormFactory $factory automaticky injektovaná továrna na formuláře
     * @param GubernatManager $gubernatManager automaticky injektovaný model pro správu gubernátu
     */
    public function __construct(FormFactory $factory, GubernatManager $gubernatManager, User $user)
    {
        $this->formFactory = $factory;
        $this->gubernatManager = $gubernatManager;
        $this->user = $user;
    }
    
    /**
     * Vytváří a vrací formulář pro automatické nakupování pozemků.
     * @param callable $onSuccess specifická funkce, která se vykoná po úspěšném odeslání formuláře
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create(callable $onSuccess)
    {
        $land = $this->gubernatManager->getLand($this->user->identity->getId());
        $form = $this->formFactory->create();
        $form->addText('land', 'Množství nakoupených pozemků')
             ->addRule(FORM::INTEGER)
             ->setRequired(TRUE);
            

        $form->addSubmit('buy', 'Koupit');

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {
               /**
                * Přidat pozemky
                * Odstranit zlato
                */
        };

        return $form;
    }
}
