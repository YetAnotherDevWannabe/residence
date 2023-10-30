<?php

namespace App\Models\Managers;

use App\Models\Rememberme;
use \DateTime;
use \PDO;

class RemembermeManager
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
	 * Method used to save a Rememberme object in DB
	 */
		public function save(Rememberme $rememberme) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$rememberme->getToken(),
				$rememberme->getUserId(),
				$rememberme->getCreationDate()->format('U'),
				$rememberme->getExpirationDate()->format('U'),
				$rememberme->getValid(),
			];

			// Build SQL request
			$sql = 'INSERT INTO rememberme(token, user_id, creation_date, expiration_date, valid) VALUES(?, ?, ?, ?, ?);';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			return $status;
		}

	/**
	 * Method used to update a Rememberme object in DB
	 */
		public function update(Rememberme $modifyRememberme) {
			// Check what parts of the User needs an update and build SQL query and params
			$modifications = 0;

			$sql = 'UPDATE rememberme SET';
			// expiration_date
			if( isset($modifyRememberme->expirationDate) ) {
				$params['expiration_date'] = $modifyRememberme->getExpirationDate()->format('U');
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'expiration_date = :expiration_date';
				$modifications++;
			}

			// valid
			if( isset($modifyRememberme->valid) ) {
				$params['valid'] = $modifyRememberme->getValid();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'valid = :valid';
				$modifications++;
			}

			// then add the id and only active accounts
			$params['token'] = $modifyRememberme->getToken();
			$sql = $sql.' WHERE token = :token;';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			return $status;
		}

	/**
	 * Method used to mark a Rememberme as invalid in DB
	 */
		public function invalidate(Rememberme $remembermeInvalid) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$remembermeInvalid->getToken()
			];

			// Build SQL request
			$sql = 'UPDATE rememberme SET valid = false WHERE token = ?;';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			return $status;
		}

	/**
	 * Method used to get one Rememberme by its token, unless marked as invalid
	 */
		public function getOneByToken(int $token) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$token
			];

			// Build SQL request
			$sql = 'SELECT * FROM rememberme WHERE token = ? AND valid = true;';

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
	 * Method used to get one Rememberme by its userId, unless marked as invalid
	 */
		public function getOneByUserId(int $userId) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$userId
			];

			// Build SQL request
			$sql = 'SELECT * FROM rememberme WHERE user_id = ? AND valid = true;';

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
	 * Method used to get all the Rememberme from the userId, unless marked as invalid
	 */
	// public function getAllByUserId(int $userId) {
	// 	// Parameters for the SQL query about to be executed (same number, same order)
	// 	$params = [
	// 		$userId
	// 	];

	// 	// Build SQL request
	// 	$sql = 'SELECT * FROM rememberme WHERE user_id = ? AND valid = true;';

	// 	// Prepare then Execute SQL request
	// 	$getAll = $this->getDb()->prepare($sql);
	// 	$getAll->execute($params);

	// 	$allInArray = $getAll->fetchAll(PDO::FETCH_ASSOC);

	// 	if ( !empty($allInArray) ) {
	// 		foreach ($allInArray as $oneInArray) {
	// 			$allInObject[] = $this->buildDomainObject($oneInArray);
	// 		}
	// 	}

	// 	return $allInObject ?? [];
	// }

	/**
	 * User builder: transform an array in object
	 */
	public function buildDomainObject(array $oneInArray) {
		$oneInObject = new Rememberme();
		$dateTimeObject = new DateTime();

		$oneInObject
			->setToken($oneInArray['token'])
			->setUserId($oneInArray['user_id'])
			->setCreationDate($dateTimeObject->setTimestamp(intval($oneInArray['creation_date'])))
			->setExpirationDate($dateTimeObject->setTimestamp(intval($oneInArray['expiration_date'])))
			->setValid($oneInArray['valid']);

		return $oneInObject;
	}
}