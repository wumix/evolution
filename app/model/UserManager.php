<?php

namespace App\Model;

use Exception;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;
use App\Classes\Race;

/**
 * Model pro správu uživatelů v systému.
 */
class UserManager extends DatabaseManager implements IAuthenticator
{
        /** Konstanty pro práci s databází. */
        const
                TABLE_USERS = 'users',
                TABLE_GUBERNATS = 'gubernats',
                COLUMN_USERS_ID = 'gubernats_fk',
                COLUMN_GUBERNATS_ID = 'gubernats_id',
                COLUMN_NAME = 'username',
                COLUMN_PASSWORD_HASH = 'password',
                COLUMN_EMAIL = 'email',
                COLUMN_ROLE = 'role',
                COLUMN_RACE = 'race',
                COLUMN_CITIZENS = 'citizens',
                COLUMN_UNEMPLOYED = 'unemployed',
                ROLE = 1;

        /**
         * Přihlásí uživatele do systému.
         * @param array $credentials přihlašovací údaje uživatele (jméno a heslo)
         * @return Identity identitu přihlášeného uživatele pro další manipulaci
         * @throws AuthenticationException Jestliže došlo k chybě při přihlášení, např. špatné heslo nebo uživatelské jméno.
         */
        public function authenticate(array $credentials) {
            list($username, $password) = $credentials; // Extrahuje potřebné přihlašovací údaje.
            // Najde a vrátí první záznam uživatele s daným jménem v databázi nebo false, pokud takový uživatel neexistuje.

            
            $user = (array) $this->database->query('SELECT users.*, gubernats.* '
                    . 'FROM users '
                    . 'LEFT JOIN gubernats ON users.users_id = gubernats.gubernats_fk '
                    . 'WHERE username = ?',$username)->fetch();

            // Ověření uživatele.
            if (!$user) {
                throw new AuthenticationException('Zadané uživatelské jméno neexistuje.', self::IDENTITY_NOT_FOUND);
            } else if (!Passwords::verify($password, $user[self::COLUMN_PASSWORD_HASH])) {
                throw new AuthenticationException('Zadané heslo není správně.', self::INVALID_CREDENTIAL);
            } else if (Passwords::needsRehash($user[self::COLUMN_PASSWORD_HASH])) { 
                $user->update([self::COLUMN_PASSWORD_HASH => Passwords::hash($password)]);
            }

            //$userAttributes = $user->toArray(); // Převede uživatelská data z databáze na PHP pole.
            unset($user[self::COLUMN_PASSWORD_HASH]); // Odstraní hash hesla z uživatelských dat (kvůli bezpečnosti).
            // Vrátí novou identitu úspěšně přihlášeného uživatele.
            return new Identity($user[self::COLUMN_USERS_ID], $user[self::COLUMN_ROLE], $user);
    }

    /**
         * Registruje nového uživatele do systému.
         * @param string $username uživatelské jméno
         * @param string $password heslo
         * @throws DuplicateNameException Jestliže uživatel s daným jménem již existuje.
         */
        public function register($username, $password, $email, $race) {
            try {
                // Pokusí se vložit nového uživatele do databáze.
                $user = $this->database->table(self::TABLE_USERS)->insert([
                    self::COLUMN_NAME => $username,
                    self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
                    self::COLUMN_EMAIL => $email,
                    self::COLUMN_ROLE => self::ROLE,
                ]);
                $citizens = ceil(Race::getCitizensPerLand($race) * 100);
                $this->database->query('INSERT INTO gubernats '
                        . '(gubernats_fk, race, citizens, unemployed) '
                        . 'VALUES (?, ?, ?, ?)',$user->users_id, $race, $citizens, $citizens);
                
                
                
            } catch (UniqueConstraintViolationException $e) {
                // Vyhodí výjimku, pokud uživatel s daným jménem již existuje.
                throw new DuplicateNameException;
            }
    }
}

/**
 * Výjimka pro duplicitní uživatelské jméno.
 */
class DuplicateNameException extends Exception
{
        // Nastavení výchozí chybové zprávy.
        protected $message = 'Uživatel s tímto jménem je již zaregistrovaný.';
}