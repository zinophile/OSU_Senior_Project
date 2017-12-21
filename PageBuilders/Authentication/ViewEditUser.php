<?php

require_once(PROJECT_PATH.'/PageBuilders/Authentication/Registration.php');

class ViewEditUser extends Registration {

   function __construct() {
      parent::__construct();
   }

   public function getAllUsers() {
      $query = <<<SQL
SELECT
   id,
   username,
   firstname,
   lastname,
   create_date,
   access_level
FROM
   users u
   JOIN user_access ua ON (u.acc_ID = ua.access_ID)
SQL;
      return $this->DB->execute($query);
   }
   
   
   public function deleteUsers($userIDs) {

      foreach($userIDs as $id) {
         
         $query = <<<SQL
DELETE FROM
   sessions
WHERE
   userid = ?
SQL;

         $this->DB->execute($query, array($id));

         if ($this->DB->getLastErrno()!=0) {
            $this->errorMessage[] = "User Could Not Be Deleted";
            return false;
         }
         
         $query = <<<SQL
DELETE FROM
   users
WHERE
   id = ?
SQL;

         $this->DB->execute($query, array($id));
         
         if ($this->DB->getLastErrno()===0) {
            $this->successMessage[] = "User Deleted";
            return true;
         }
         else {
            $this->errorMessage[] = "User Could Not Be Deleted";
            return false;
         }
         
      }
      
      
   }
   
   public function getUserInfo($userID) {
      $query = <<<SQL
SELECT
   id,
   firstname,
   lastname,
   acc_ID,
   access_level
FROM
   users u
   JOIN user_access ua ON (u.acc_ID = ua.access_ID)
WHERE
   id = ?
SQL;
      return $this->DB->execute($query,[$userID]);

   }
   
   public function editUser($userID, $firstname, $lastname, $accessID) {

      $query = <<<SQL
   UPDATE
      users
   SET
      firstname = ?,
      lastname = ?,
      acc_ID = ?
   WHERE
      id = ?
SQL;

      $this->DB->execute($query, array($firstname, $lastname,$accessID, $userID));
      
      if ($this->DB->getLastErrno()===0) {
         $this->successMessage[] = "User Updated";
         return true;
      }
      else {
         $this->errorMessage[] = "User Could Not Be Updated";
         return false;
      }
      
   }
   
}