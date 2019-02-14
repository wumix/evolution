<?php

namespace App\Forms;

use App\Model\BuildingsManager;
use Nette\Application\UI\Form;
use Nette\SmartObject;
use Nette\Security\User;

class SpecialBuildingsFormFactory {

    use SmartObject;

    private $formFactory;
    private $buildingsManager;
    private $user;
    private $data;

    public function __construct(
            FormFactory $factory,
            BuildingsManager $buildingsManager,
            User $user)
    {
        $this->formFactory = $factory;
        $this->buildingsManager = $buildingsManager;
        $this->user = $user;
    }

    /**
     * Vytváří a vrací formulář pro stavění a bourání budov
     * @return Form formulář pro automatické nakupování pozemků
     */
    public function create($data) 
    {
        $this->data = $data;
        $userID = $this->user->identity->getId();
        $buildings = array(
            array('building1', 'Radnice'),
            array('building2', 'Rozcestí'),
            array('building3', 'Hradby'),
            array('building4', 'Cvičiště'),
            array('building5', 'Magické oko'),
            array('building6', 'Pivnice'),
            array('building7', 'Chrám'),
            array('building8', 'Palác času')
        );
        $built = $this->buildingsManager->getSpecialBuildingsData($userID);
        $form = $this->formFactory->create();
        for ($i = 0; $i < count($buildings); $i++) {
            if($built[$buildings[$i][0]] != 100) {
                $form->addSubmit('build'.$buildings[$i][0], 'Začít stavět');
            }
        }
        $form->onSuccess[] = [$this, 'specialBuildingsFormSucceeded'];
        dump($this->data);
        return $form;
    }

    public function specialBuildingsFormSucceeded(Form $form) {
        $presenter = $form->getPresenter();
        $building = substr($form->isSubmitted()->name,5);
        $userID = $this->user->identity->getId();
        if($this->data['special_building'] != NULL) {
            if($this->data[$this->data['special_building']] != 100) {
                $this->buildingsManager->destroySpecialBuilding($userID, $this->data['special_building']);
            }
        }       
        $this->buildingsManager->buildSpecialBuilding($building, $userID);
        $presenter->flashMessage('Budovy byly postaveny.', 'notice');
        
 

    }
}
