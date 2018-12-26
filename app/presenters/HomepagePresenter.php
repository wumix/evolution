<?php

namespace App\Presenters;

use App\Forms\SignInFormFactory;
use App\Forms\SignUpFormFactory;
use App\Presenters\BasePresenter;
use Nette\Application\AbortException;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * Presenter pro vykreslování administrační sekce.
 * @package App\CoreModule\Presenters
 */
class HomepagePresenter extends BasePresenter
{
    /** @var SignInFormFactory */
    private $signInFactory;  

    /**
     * HomepagePresenter constructor.
     * @param SignInFormFactory $signInFactory
     * @param SignUpFormFactory $signUpFactory
     */
    public function __construct(SignInFormFactory $signInFactory)
    {
        $this->signInFactory = $signInFactory;
    }

    /**
     * Před vykreslováním stránky pro přihlášení přesměruje do administrace, pokud je uživatel již přihlášen.
     * @throws AbortException Když dojde k přesměrování.
     */
    public function actionLogin()
    {
        if ($this->user->isLoggedIn()) {$this->redirect('Gubernat:');}
    }

    /** Předá jméno přihlášeného uživatele do šablony administrační stránky. */
    public function renderDefault()
    {
        if ($this->user->isLoggedIn()) {$this->redirect('Gubernat:');}
    }

    /**
     * Vytváří a vrací přihlašovací formulář pomocí továrny.
     * @return Form přihlašovací formulář
     */
    protected function createComponentSignInForm()
    {
        return $this->signInFactory->create(function (Form $form, ArrayHash $values) {
            $this->flashMessage('Byl jste úspěšně přihlášen.');
            $this->redirect('Gubernat:default');
        });
    }

}