<?php

namespace App\Models;

use \DateTime;

class User
{
	public $id;
	public $email;
	public $passwordHash;
	public $name;
	public $avatar;
	public $registrationDate;
	public $deleted;


	/**
	 * GETTERS
	 */
		public function getId() {
			return $this->id;
		}

		public function getEmail() {
			return $this->email;
		}

		public function getPasswordHash() {
			return $this->passwordHash;
		}

		public function getName() {
			return $this->name;
		}

		public function getAvatar() {
			return $this->avatar;
		}

		public function getRegistrationDate() {
			$dateTimeObject = new DateTime();
			return $dateTimeObject->setTimestamp($this->registrationDate);
			// return $this->registrationDate;
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

		public function setEmail(string $email) {
			$this->email = $email;
			return $this;
		}

		public function setPasswordHash(string $passwordHash) {
			$this->passwordHash = $passwordHash;
			return $this;
		}

		public function setName(string $name) {
			$this->name = $name;
			return $this;
		}

		public function setAvatar(string $avatar) {
			$this->avatar = $avatar;
			return $this;
		}

		public function setRegistrationDate(DateTime $registrationDate) {
			$this->registrationDate = $registrationDate->format('U');
			return $this;
		}

		public function setDeleted(bool $deleted) {
			$this->deleted = $deleted;
			return $this;
		}


	/**
	 * __toString() : show object as a string
	 */
		public function __toString() {
			return '#'.$this->getId().'. '.$this->getEmail().' - '.$this->getName().' - '.$this->getRegistrationDate()->format('Y-m-d H:i:s').' - deleted: '.boolval($this->getDeleted());
		}
}