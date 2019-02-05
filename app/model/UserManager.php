<?php

namespace App\Model;

use Exception;
use Nette\Database\UniqueConstraintViolationException;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 * Model pro správu uživatelů v systému.
 */
class UserManager extends DatabaseManager implements IAuthenticator
{
        /** Konstanty pro práci s databází. */
        const
                TABLE_NAME = 'users',
                COLUMN_ID = 'userID',
                COLUMN_NAME = 'username',
                COLUMN_PASSWORD_HASH = 'password',
                COLUMN_EMAIL = 'email',
                COLUMN_ROLE = 'role',
                ROLE = 1;

        /**
         * Přihlásí uživatele do systému.
         * @param array $credentials přihlašovací údaje uživatele (jméno a heslo)
         * @return Identity identitu přihlášeného uživatele pro další manipulaci
         * @throws AuthenticationException Jestliže došlo k chybě při přihlášení, např. špatné heslo nebo uživatelské jméno.
         */
        public function authenticate(array $credentials)
        {
                list($username, $password) = $credentials; // Extrahuje potřebné přihlašovací údaje.

                // Najde a vrátí první záznam uživatele s daným jménem v databázi nebo false, pokud takový uživatel neexistuje.
                $user = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

                // Ověření uživatele.
                if (!$user) { // Vyhodí výjimku, pokud uživatel neexituje.
                        throw new AuthenticationException('Zadané uživatelské jméno neexistuje.', self::IDENTITY_NOT_FOUND);
                } else if (!Passwords::verify($password, $user[self::COLUMN_PASSWORD_HASH])) { // Ověří zadané heslo.
                        // Vyhodí výjimku, pokud je heslo špatně.
                        throw new AuthenticationException('Zadané heslo není správně.', self::INVALID_CREDENTIAL);
                } else if (Passwords::needsRehash($user[self::COLUMN_PASSWORD_HASH])) { // Zjistí zda heslo potřebuje rehashovat.
                        // Rehashuje heslo (bezpečnostní opatření).
                        $user->update([self::COLUMN_PASSWORD_HASH => Passwords::hash($password)]);
                }

                // Příprava atributů z databáze pro identitu úspěšně přihlášeného uživatele.
                $userAttributes = $user->toArray(); // Převede uživatelská data z databáze na PHP pole.
                unset($userAttributes[self::COLUMN_PASSWORD_HASH]); // Odstraní hash hesla z uživatelských dat (kvůli bezpečnosti).

                // Vrátí novou identitu úspěšně přihlášeného uživatele.
                return new Identity($user[self::COLUMN_ID], $user[self::COLUMN_ROLE], $userAttributes);
        }

        /**
         * Registruje nového uživatele do systému.
         * @param string $username uživatelské jméno
         * @param string $password heslo
         * @throws DuplicateNameException Jestliže uživatel s daným jménem již existuje.
         */
        public function register($username, $password, $email, $race)
        {
                try {
                        // Pokusí se vložit nového uživatele do databáze.
                        $row = $this->database->table(self::TABLE_NAME)->insert([
                                self::COLUMN_NAME => $username,
                                self::COLUMN_PASSWORD_HASH => Passwords::hash($password),
                                self::COLUMN_EMAIL => $email,
                                self::COLUMN_ROLE => self::ROLE,
                        ]);
                       
                        $this->database->table('gubernats')->insert([
                                'users_userID' => $row->userID,
                                'race' => $race,
                        ]);
                        
                        $this->database->table('resources')->insert([
                                'users_userID' => $row->userID,
                        ]);
                        
                        $this->database->table('proffesions')->insert([
                                'users_userID' => $row->userID,
                        ]);
                        
                        $this->database->table('buildings')->insert([
                                'users_userID' => $row->userID,
                        ]);
                        
                        $this->database->table('special_buildings')->insert([
                                'users_userID' => $row->userID,
                        ]);
                        
                        
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