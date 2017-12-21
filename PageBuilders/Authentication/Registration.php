<?php

require_once(PROJECT_PATH.'/PageBuilders/Authentication/AuthenticationManager.php');

class Registration extends AuthenticationManager {

   function __construct() {
      parent::__construct();
   }

   public function registerUser($email, $accessLevel, $firstName, $lastName) {
      return $this->authenticator->register($email, $accessLevel, $firstName, $lastName);
   }

   public function getAccessLevels() {
      return $this->authenticator->getAccessLevels();
   }

   public function getRegions() {
      $query = <<<SQL
SELECT
   *
FROM
   region
SQL;

		return $this->DB->execute($query);

   }
}