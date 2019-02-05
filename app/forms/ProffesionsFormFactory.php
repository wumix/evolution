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
    public function __construct(FormFactory $factory, GubernatManager $gubernatManager, ProffesionsManager $proffesionsManager, User $user) {
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
    public function create(callable $onSuccess, $proffesion, $label) {
        $form = $this->formFactory->create();
        $form->addText($proffesion, $label)
                ->addRule(FORM::INTEGER)
                ->setRequired(TRUE);

        $form->addSubmit('add', 'Přidat');
        $form->addSubmit('remove', 'Odebrat');

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {
            /**
             * Přidat nebo odebrat lidi do profese
             */
        };

        return $form;
    }

}
