<?php

namespace App\Models\Managers;

use App\Models\Residence;
use \PDO;

class ResidenceManager
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
	 * Method used to save a Residence object in DB
	 */
		public function save(Residence $residence) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$residence->getUserId(),
				$residence->getName(),
				$residence->getAddress(),
				$residence->getPostalCode(),
				$residence->getCity(),
				$residence->getType(),
			];

			// Build SQL request
			$sql = 'INSERT INTO residence(user_id, name, address, postal_code, city, type) VALUES(?, ?, ?, ?, ?, ?);';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			
			$residence->setId($this->getDb()->lastInsertId());
			return $status;
		}

	/**
	 * Method used to update a Residence object in DB
	 */
		public function update(Residence $modifyResidence) {
			// Check what parts of the Residence needs an update and build SQL query and params
			$modifications = 0;

			$sql = 'UPDATE residence SET';
			// name
			if( isset($modifyResidence->name) ) {
				$params['name'] = $modifyResidence->getName();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'name = :name';
				$modifications++;
			}

			// address
			if( isset($modifyResidence->address) ) {
				$params['address'] = $modifyResidence->getAddress();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'address = :address';
				$modifications++;
			}

			// postal_code
			if( isset($modifyResidence->postalCode) ) {
				$params['postal_code'] = $modifyResidence->getPostalCode();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'postal_code = :postal_code';
				$modifications++;
			}

			// city
			if( isset($modifyResidence->city) ) {
				$params['city'] = $modifyResidence->getCity();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'city = :city';
				$modifications++;
			}

			// type
			if( isset($modifyResidence->type) ) {
				$params['type'] = $modifyResidence->getType();
				$sep = ($modifications > 0) ? ', ' : ' ';
				$sql = $sql.$sep.'type = :type';
				$modifications++;
			}

			// then add the id
			$params['id'] = $modifyResidence->getId();
			$sql = $sql.' WHERE id = :id;';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			return $status;
		}

	/**
	 * Method used to mark a Residence as deleted in DB
	 */
		public function delete(Residence $residenceDelete) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$residenceDelete->getId()
			];

			// Build SQL request
			$sql = 'UPDATE residence SET deleted = true WHERE id = ?;';

			// Prepare then Execute SQL request
			$preparedSQL = $this->getDb()->prepare($sql);
			$status = $preparedSQL->execute($params);
			return $status;
		}

	/**
	 * Method used to get one Residence by its id, unless marked as deleted
	 */
		public function getOneById(int $id) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$id
			];

			// Build SQL request
			$sql = 'SELECT * FROM residence WHERE id = ? AND deleted = false;';

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
	 * Method used to get all Residence by their userId, unless marked as deleted
	 */
		public function getAllByUserId(int $userId) {
			// Parameters for the SQL query about to be executed (same number, same order)
			$params = [
				$userId
			];

			// Build SQL request
			$sql = 'SELECT * FROM residence WHERE user_id = ? AND deleted = false;';

			// Prepare then Execute SQL request
			$getAll = $this->db->prepare($sql);
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
		$oneInObject = new Residence();

		$oneInObject
			->setId($oneInArray['id'])
			->setUserId($oneInArray['user_id'])
			->setName($oneInArray['name'])
			->setAddress($oneInArray['address'])
			->setPostalCode($oneInArray['postal_code'])
			->setCity($oneInArray['city'])
			->setType($oneInArray['type']);

		return $oneInObject;
	}
}