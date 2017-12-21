<?php

// This is a BossaNova wrapper for joshcam/PHP-MySQLi-Database-Class third party database class
// Documentation for the class can be found: https://github.com/joshcam/PHP-MySQLi-Database-Class


class DB {

   protected $link;

   protected $query;

   protected $arguments;

   protected $dbHandler;

   function __construct($customLink=null) {
      $this->link = isset($customLink) ? $customLink : MysqlConn::getConnection();
      $this->dbHandler = new mysqliDb($this->link);
   }

   public function setQuery($query) {
      $this->query = $query;
   }

   public function setArguments($arguments) {
      $this->arguments = $arguments;
   }

   public function execute($query=null,$arguments=null) {
      $this->query = isset($query) ? $query : $this->query;
      $this->arguments = isset($arguments) ? $arguments : $this->arguments;

      $results = null;

      if (count($this->arguments) > 0) {
         $results = $this->dbHandler->rawQuery($this->query, $this->arguments);
      }
      else {
         $results = $this->dbHandler->rawQuery($this->query);
      }

      return $results;

   }

   public function setLink($linkName) {
      $this->link = $linkName;
   }

   public function selectDB($dbName) {
      mysqli_select_db($this->link,$dbName);
   }

   public function getdbHandler() {
      return $this->dbHandler;
   }

   public function getLastErrno() {
      return $this->dbHandler->getLastErrno();
   }

   public function getLastError() {
      return $this->dbHandler->getLastError();
   }

}