<?php

namespace App\Model;

use Nette\Database\Context;
use Nette\SmartObject;

/**
 * Základní model pro všechny ostatní databázové modely aplikace.
 * Poskytuje přístup k práci s databází.
 */
abstract class DatabaseManager
{
    use SmartObject;

    /** @var Context Služba pro práci s databází. */
    protected $database;

    /**
     * Konstruktor s injektovanou službou pro práci s databází.
     * @param Context $database automaticky injektovaná Nette služba pro práci s databází
     */
    public function __construct(Context $database)
    {
        $this->database = $database;
    }
}
