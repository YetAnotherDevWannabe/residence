<?php

namespace App\Models;

use \DateTime;

class Residence
{
	public $id;
	public $userId;
	public $name;
	public $address;
	public $postalCode;
	public $city;
	public $type;
	public $deleted;


	/**
	 * GETTERS
	 */
		public function getId() {
			return $this->id;
		}

		public function getUserId() {
			return $this->userId;
		}

		public function getName() {
			return $this->name;
		}

		public function getAddress() {
			return $this->address;
		}

		public function getPostalCode() {
			return $this->postalCode;
		}

		public function getCity() {
			return $this->city;
		}

		public function getType() {
			return $this->type;
		}

		public function getDeleted() {
			return $this->deleted;
		}


	/**
	 * SETTERS
	 */
		public function setId(int $id) {
			$this->id = $id;
			return $this;
		}

		public function setUserId(string $userId) {
			$this->userId = $userId;
			return $this;
		}

		public function setName(string $name) {
			$this->name = $name;
			return $this;
		}

		public function setAddress(string $address) {
			$this->address = $address;
			return $this;
		}

		public function setPostalCode(string $postalCode) {
			$this->postalCode = $postalCode;
			return $this;
		}

		public function setCity(string $city) {
			$this->city = $city;
			return $this;
		}

		public function setType(string $type) {
			$this->type = $type;
			return $this;
		}

		public function setDeleted(string $deleted) {
			$this->deleted = $deleted;
			return $this;
		}


	/**
	 * __toString() : show object as a string
	 */
		public function __toString() {
			return '#'.$this->getId().'. Type: '.$this->getType().'. Name: '.$this->getName().' - Address: '.$this->getAddress().' - Postal code: '.$this->getPostalCode().' - City: '.$this->getCity().' - Deleted: '.boolval($this->getCity());
		}
}