<?php

namespace App\Models;

use \DateTime;

class Rememberme
{
	public $token;
	public $userId;
	public $creationDate;
	public $expirationDate;
	public $valid;


	/**
	 * GETTERS
	 */
		public function getToken() {
			return $this->token;
		}

		public function getUserId() {
			return $this->userId;
		}

		public function getCreationDate() {
			$dateTimeObject = new DateTime();
			return $dateTimeObject->setTimestamp($this->creationDate);
		}

		public function getExpirationDate() {
			$dateTimeObject = new DateTime();
			return $dateTimeObject->setTimestamp($this->expirationDate);
		}

		public function getValid() {
			return $this->valid;
		}


	/**
	 * SETTERS
	 */
		public function setToken(string $token) {
			$this->token = $token;
			return $this;
		}

		public function setUserId(int $userId) {
			$this->userId = $userId;
			return $this;
		}

		public function setCreationDate(DateTime $creationDate) {
			$this->creationDate = $creationDate->format('U');
			return $this;
		}

		public function setExpirationDate(DateTime $expirationDate) {
			$this->expirationDate = $expirationDate->format('U');
			return $this;
		}

		public function setValid(bool $valid) {
			$this->valid = $valid;
			return $this;
		}


	/**
	 * __toString() : show object as a string
	 */
		public function __toString() {
			return ' -token:'.$this->getToken().' -user_id:'.$this->getUserId().' -expiration_date:'.$this->getExpirationDate()->format('Y.m.d H:i:s');
		}
}