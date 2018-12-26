<?php

namespace App\Forms;

use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;

/**
 * Továrna na přihlašovací formulář.
 * @package App\Forms
 */
class SignInFormFactory
{
    use SmartObject;

    /** @var FormFactory Továrna na formuláře. */
    private $formFactory;

    /** @var User Uživatel. */
    private $user;

    /**
     * Konstruktor s injektovanou továrnou na formuláře a uživatelem.
     * @param FormFactory $factory automaticky injektovaná továrna na formuláře
     * @param User        $user    automaticky injektovaný uživatel
     */
    public function __construct(FormFactory $factory, User $user)
    {
        $this->formFactory = $factory;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací přihlašovací formulář.
     * @param callable $onSuccess specifická funkce, která se vykoná po úspěšném odeslání formuláře
     * @return Form přihlašovací formulář
     */
    public function create(callable $onSuccess)
    {
        $form = $this->formFactory->create();
        $form->addText('username', 'Jméno')->setRequired();
        $form->addPassword('password', 'Heslo');
        $form->addSubmit('login', 'Přihlásit');

        $form->onSuccess[] = function (Form $form, ArrayHash $values) use ($onSuccess) {
            try {
                // Zkusíme se přihlásit.
                $this->user->login($values->username, $values->password);
                $onSuccess($form, $values); // Zavoláme specifickou funkci.
            } catch (AuthenticationException $e) {
                // Přidáme chybovou zprávu alespoň do formuláře.
                $form->addError('Zadané přihlašovací jméno nebo heslo nejsou správně.');
            }
        };

        return $form;
    }
}