<?php

namespace App\Presenters;

use App\Forms\SignUpFormFactory;
use App\Presenters\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Presenter pro vykreslování registrace.
 */
class RegisterPresenter extends BasePresenter {

    private $signUpFactory;

    /**
     * @param SignUpFormFactory $signUpFactory
     */
    public function __construct(SignUpFormFactory $signUpFactory) {
        parent::__construct();
        $this->signUpFactory = $signUpFactory;
    }

    /** Předá jméno přihlášeného uživatele do šablony administrační stránky. */
    public function renderDefault() {
        if ($this->user->isLoggedIn()) {
            $this->template->username = $this->user->identity->username;
        }
    }

    /**
     * Vytváří a vrací přihlašovací formulář pomocí továrny.
     * @return Form přihlašovací formulář
     */
    protected function createComponentSignUpForm() {
        return $this->signUpFactory->create(function (Form $form, ArrayHash $values) {
                    $this->flashMessage('Byl jste úspěšně zaregistrován.');
                    $this->user->login($values->username, $values->password); // Přihlásíme se.
                    $this->redirect('Gubernat:default');
                });
    }

}
