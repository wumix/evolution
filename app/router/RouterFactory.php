<?php

namespace App;

use Nette\Application\IRouter;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;
use Nette\StaticClass;

/**
 * Továrna na routovací pravidla.
 * Řídí směrování a generovaní URL adres v celé aplikaci.
 * @package App
 */
class RouterFactory
{
    use StaticClass;

    /**
     * Vytváří a vrací seznam routovacích pravidel pro aplikaci.
     * @return IRouter výsledný router pro aplikaci
     */
    public static function createRouter()
    {
        $router = new RouteList;
        $router[] = new Route('<presenter>/<action>/[<id>]', 'Homepage:default'); 
        return $router;
    }
}