<?php

namespace App\Models\Managers;

use App\Models\User;
use \DateTime;
use \PDO;

class UserManager
{
	/**
	 * Stores an active PDO DB connection through connectDb() in the __construct()
	 */
		private $db;


	/**
	 * Getters/setters
	 */
		public function getDb() {
			return $this->db;
		}

		public function setDb(PDO $db) {
			$this->db = $db;
		}


	/**
	 * Constructor used to hydrate $db with a PDO instance recovered via the function connectDb()
	 */
		public function __construct() {
			$this->setDb(connectDb());
		}


	/**
	 * Method used to save a User object in DB
	 */
		public function save(User $userToSave) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$userToSave->getEmail(),
				$userToSave->getPasswordHash(),
				$userToSave->getName(),
				$userToSave->getAvatar(),
				$userToSave->getRegistrationDate()->format('U'),
				$userToSave->getDeleted(),
			];

			// Build SQL request
			$sql = 'INSERT INTO user(email, password_hash, name, avatar, registration_date, deleted) VALUES(?, ?, ?, ?, ?, ?);';

			// Prepare then Execute SQL request
			$insertUser = $this->getDb()->prepare($sql);
			$status = $insertUser->execute($params);

			$userToSave->setId($this->getDb()->lastInsertId());
			return $status;
		}

	/**
	 * Méthode servant à modifier un objet d' utilisateur en DB
	 */
		public function update(User $userToModify) {
			// Check what parts of the User needs an update and build SQL query and params
			$modifications = 0;

			$sql = 'UPDATE user SET';
			// email
			if( isset($userToModify->email) ) {
				$params['email'] = $userToModify->getEmail();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'email = :email';
				$modifications++;
			}

			// name
			if( isset($userToModify->name) ) {
				$params['name'] = $userToModify->getName();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'name = :name';
				$modifications++;
			}

			// password_hash
			if( isset($userToModify->passwordHash) ) {
				$params['password_hash'] = $userToModify->getPasswordHash();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'password_hash = :password_hash';
				$modifications++;
			}

			// then add the id and only active accounts
			$params['id'] = $userToModify->getId();
			$sql = $sql.' WHERE id = :id AND deleted = false;';

			// Prepare then Execute SQL request
			$modifyUser = $this->getDb()->prepare($sql);
			$status = $modifyUser->execute($params);
			return $status;
		}

	/**
	 * Method used to mark a User as deleted in DB
	 */
		public function delete(User $userToDelete) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$userToDelete->getId()
			];

			// Build SQL request
			$sql = 'UPDATE user SET deleted = true WHERE id = ? AND deleted = false;';

			// Prepare then Execute SQL request
			$deleteUser = $this->getDb()->prepare($sql);
			$status = $deleteUser->execute($params);
			return $status;
		}

	/**
	 * Method used to get one User by one of its properties, unless user is marked as deleted
	 */
		public function getOneBy(string $property, $value) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$value
			];

			// Build SQL request
			$sql = 'SELECT * FROM user WHERE '.$property.' = ? AND deleted = false;';

			// Prepare then Execute SQL request
			$getOne = $this->db->prepare($sql);
			$getOne->execute($params);

			$oneInArray = $getOne->fetch(PDO::FETCH_ASSOC);

			if ( !empty($oneInArray) ) {
				$oneInObject = $this->buildDomainObject($oneInArray);
			}

			return $oneInObject ?? null;
		}

	/**
	 * Method used to get one User by its id, unless file is marked as deleted
	 */
		public function getOneById(int $id) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$id
			];

			// Build SQL request
			$sql = 'SELECT * FROM user WHERE id = ? AND deleted = false;';

			// Prepare then Execute SQL request
			$getOne = $this->db->prepare($sql);
			$getOne->execute($params);

			$oneInArray = $getOne->fetch(PDO::FETCH_ASSOC);

			if ( !empty($oneInArray) ) {
				$oneInObject = $this->buildDomainObject($oneInArray);
			}

			return $oneInObject ?? null;
		}

	/**
	 * Method used to get ALL users in DB, but the users marked as deleted
	 */
		public function getAll() {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [];

			// Build SQL request
			$sql = 'SELECT * FROM user WHERE deleted = false;';

			// Prepare then Execute SQL request
			$getAll = $this->getDb()->prepare($sql);
			$getAll->execute($params);

			$allInArray = $getAll->fetchAll(PDO::FETCH_ASSOC);

			if ( !empty($allInArray) ) {
				foreach ($allInArray as $oneInArray) {
					$allInObject[] = $this->buildDomainObject($oneInArray);
				}
			}

			return $allInObject ?? [];
		}

	/**
	 * User builder: transform an array in object
	 */
	public function buildDomainObject(array $oneInArray) {
		$oneInObject = new User();
		$dateTimeObject = new DateTime();

		$oneInObject
			->setId($oneInArray['id'])
			->setEmail($oneInArray['email'])
			->setPasswordHash($oneInArray['password_hash'])
			->setName($oneInArray['name'])
			->setAvatar($oneInArray['avatar'])
			->setRegistrationDate($dateTimeObject->setTimestamp(intval($oneInArray['registration_date'])))
			->setDeleted($oneInArray['deleted']);

		return $oneInObject;
	}
}